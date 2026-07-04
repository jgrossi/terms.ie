<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClientController extends Controller
{
    public function index(): Response
    {
        $clients = auth()->user()->clients()->latest()->get();

        return Inertia::render('clients/Index', [
            'clients' => $clients->map(fn (Client $client) => [
                'id'         => $client->id,
                'name'       => $client->name,
                'email'      => $client->email,
                'created_at' => $client->created_at->toIso8601String(),
            ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('clients/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $client = auth()->user()->clients()->create($data);

        return redirect()->route('app.clients.show', $client)->with('toast', 'Client created.');
    }

    public function show(Client $client): Response
    {
        abort_unless($client->user_id === auth()->id(), 403);

        $signatures = $client->signatures()->with('termVersion.term')->latest()->get();

        return Inertia::render('clients/Show', [
            'client' => [
                'id'             => $client->id,
                'name'           => $client->name,
                'email'          => $client->email,
                'has_signatures' => $signatures->isNotEmpty(),
            ],
            'signatures' => $signatures->map(fn ($sig) => [
                'id'         => $sig->id,
                'term_name'  => $sig->termVersion->term->name,
                'version'    => $sig->termVersion->version,
                'status'     => $sig->status->value,
                'is_expired' => $sig->isExpired(),
                'created_at' => $sig->created_at->toIso8601String(),
            ]),
        ]);
    }

    public function edit(Client $client): Response
    {
        abort_unless($client->user_id === auth()->id(), 403);

        return Inertia::render('clients/Edit', [
            'client' => $client->only('id', 'name', 'email'),
        ]);
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        abort_unless($client->user_id === auth()->id(), 403);

        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $client->update($data);

        return redirect()->route('app.clients.show', $client)->with('toast', 'Client updated.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        abort_unless($client->user_id === auth()->id(), 403);

        $client->delete();

        return redirect()->route('app.clients.index')->with('toast', 'Client deleted.');
    }
}
