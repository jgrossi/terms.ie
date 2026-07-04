<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Signature;
use App\Models\Term;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class SignatureController extends Controller
{
    public function create(Term $term): Response
    {
        abort_unless($term->user_id === auth()->id(), 403);

        $clients = auth()->user()->clients()->orderBy('name')->get();

        return Inertia::render('signatures/Create', [
            'term'     => $term->only('id', 'name'),
            'clients'  => $clients->map(fn (Client $client) => [
                'id'    => $client->id,
                'name'  => $client->name,
                'email' => $client->email,
            ]),
            'userVars' => $term->extractUserVariables(),
        ]);
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

    public function createForClient(Client $client): Response
    {
        abort_unless($client->user_id === auth()->id(), 403);

        $terms = auth()->user()->terms()->latest()->get();

        return Inertia::render('signatures/CreateForClient', [
            'client' => $client->only('id', 'name'),
            'terms'  => $terms->map(fn (Term $term) => [
                'id'        => $term->id,
                'name'      => $term->name,
                'variables' => $term->extractUserVariables(),
            ]),
        ]);
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

    public function show(Signature $signature): Response
    {
        abort_unless($signature->client->user_id === auth()->id(), 403);

        return Inertia::render('signatures/Show', [
            'signature' => [
                'id'           => $signature->id,
                'term_name'    => $signature->termVersion->term->name,
                'version'      => $signature->termVersion->version,
                'status'       => $signature->status->value,
                'is_pending'   => $signature->isPending(),
                'is_signed'    => $signature->isSigned(),
                'client'       => [
                    'id'    => $signature->client->id,
                    'name'  => $signature->client->name,
                    'email' => $signature->client->email,
                ],
                'variables'    => $signature->variables ?? [],
                'body'         => $signature->render(),
                'signed_name'  => $signature->signed_name,
                'signed_at'    => $signature->signed_at?->toIso8601String(),
                'signed_ip'    => $signature->signed_ip,
                'content_hash' => $signature->content_hash,
            ],
        ]);
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
