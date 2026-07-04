<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Signature;
use App\Models\Term;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SignatureController extends Controller
{
    public function create(Term $term): View
    {
        abort_unless($term->user_id === auth()->id(), 403);

        $clients = auth()->user()->clients()->orderBy('name')->get();
        $userVars = $term->extractUserVariables();

        return view('app.signatures.create', compact('term', 'clients', 'userVars'));
    }

    public function store(Request $request, Term $term): RedirectResponse
    {
        abort_unless($term->user_id === auth()->id(), 403);

        $userVars = $term->extractUserVariables();

        $rules = [
            'client_id' => ['required', Rule::exists('clients', 'id')->where('user_id', auth()->id())],
        ];

        foreach ($userVars as $var) {
            $rules["variables.{$var}"] = ['required', 'string', 'max:500'];
        }

        $data = $request->validate($rules);

        $signature = Signature::create([
            'client_id'       => $data['client_id'],
            'term_version_id' => $term->latestVersion->id,
            'variables'       => $data['variables'] ?? [],
        ]);

        return redirect()->route('app.signatures.show', $signature)->with('toast', 'Term assigned to client.');
    }

    public function createForClient(Client $client): View
    {
        abort_unless($client->user_id === auth()->id(), 403);

        $terms = auth()->user()->terms()->latest()->get();

        return view('app.signatures.create-for-client', compact('client', 'terms'));
    }

    public function storeForClient(Request $request, Client $client): RedirectResponse
    {
        abort_unless($client->user_id === auth()->id(), 403);

        $userVars = [];
        $term = Term::find($request->input('term_id'));
        if ($term && $term->user_id === auth()->id()) {
            $userVars = $term->extractUserVariables();
        }

        $rules = [
            'term_id' => ['required', Rule::exists('terms', 'id')->where('user_id', auth()->id())],
        ];

        foreach ($userVars as $var) {
            $rules["variables.{$var}"] = ['required', 'string', 'max:500'];
        }

        $data = $request->validate($rules);

        $term = Term::find($data['term_id']);

        $signature = Signature::create([
            'client_id'       => $client->id,
            'term_version_id' => $term->latestVersion->id,
            'variables'       => $data['variables'] ?? [],
        ]);

        return redirect()->route('app.signatures.show', $signature)->with('toast', 'Term assigned to client.');
    }

    public function variableFields(Term $term): View
    {
        abort_unless($term->user_id === auth()->id(), 403);

        $userVars = $term->extractUserVariables();

        return view('app.signatures._variable-fields', compact('userVars'));
    }

    public function show(Signature $signature): View
    {
        abort_unless($signature->client->user_id === auth()->id(), 403);

        return view('app.signatures.show', compact('signature'));
    }

    public function destroy(Signature $signature): RedirectResponse
    {
        abort_unless($signature->client->user_id === auth()->id(), 403);
        abort_unless($signature->isPending(), 403, 'Cannot revoke a signed document.');

        $term = $signature->termVersion->term;
        $signature->delete();

        return redirect()->route('app.terms.show', $term)->with('toast', 'Signature request revoked.');
    }
}
