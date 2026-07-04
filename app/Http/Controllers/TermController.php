<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TermController extends Controller
{
    public function index()
    {
        $terms = auth()->user()->terms()->withCount('versions')->latest()->get();

        return Inertia::render('terms/Index', [
            'terms' => $terms->map(fn (Term $term) => [
                'id'             => $term->id,
                'name'           => $term->name,
                'variables'      => $term->extractUserVariables(),
                'versions_count' => $term->versions_count,
                'updated_at'     => $term->updated_at->toIso8601String(),
            ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('terms/Create', [
            'reserved' => Term::RESERVED,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $term = auth()->user()->terms()->create($data);
        $term->versions()->create(['version' => 1, 'body' => $term->body]);

        return redirect()->route('app.terms.show', $term)->with('toast', 'Term created.');
    }

    public function show(Term $term)
    {
        abort_unless($term->user_id === auth()->id(), 403);

        $versions = $term->versions()->latest('version')->get();

        return Inertia::render('terms/Show', [
            'term' => [
                'id'                 => $term->id,
                'name'               => $term->name,
                'body'               => $term->body,
                'updated_at'         => $term->updated_at->toIso8601String(),
                'version'            => $versions->first()?->version ?? 1,
                'has_signatures'     => $term->hasSignatures(),
                'user_variables'     => $term->extractUserVariables(),
                'reserved_variables' => array_values(
                    array_intersect($term->extractVariables(), Term::RESERVED)
                ),
            ],
            'versions' => $versions->map(fn ($version) => [
                'version'    => $version->version,
                'created_at' => $version->created_at->toIso8601String(),
            ]),
        ]);
    }

    public function edit(Term $term)
    {
        abort_unless($term->user_id === auth()->id(), 403);

        return Inertia::render('terms/Edit', [
            'term'     => $term->only('id', 'name', 'body'),
            'reserved' => Term::RESERVED,
        ]);
    }

    public function update(Request $request, Term $term)
    {
        abort_unless($term->user_id === auth()->id(), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $term->fill($data);

        if ($term->isDirty('body')) {
            $term->save();
            $next = $term->versions()->max('version') + 1;
            $term->versions()->create(['version' => $next, 'body' => $term->body]);
        } else {
            $term->save();
        }

        return redirect()->route('app.terms.show', $term)->with('toast', 'Term updated.');
    }

    public function destroy(Term $term)
    {
        abort_unless($term->user_id === auth()->id(), 403);
        abort_if($term->hasSignatures(), 403, 'Cannot delete a term that has been assigned to clients.');

        $term->delete();

        return redirect()->route('app.terms.index')->with('toast', 'Term deleted.');
    }
}
