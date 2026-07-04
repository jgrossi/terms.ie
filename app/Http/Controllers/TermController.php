<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index()
    {
        $terms = auth()->user()->terms()->withCount('versions')->latest()->get();

        return view('app.terms.index', compact('terms'));
    }

    public function create()
    {
        return view('app.terms.create');
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

        return view('app.terms.show', compact('term', 'versions'));
    }

    public function edit(Term $term)
    {
        abort_unless($term->user_id === auth()->id(), 403);

        return view('app.terms.edit', compact('term'));
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
