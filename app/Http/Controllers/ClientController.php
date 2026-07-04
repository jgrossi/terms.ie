<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $clients = auth()->user()->clients()->latest()->get();

        return view('app.clients.index', compact('clients'));
    }

    public function create(): View
    {
        return view('app.clients.create');
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

    public function show(Client $client): View
    {
        abort_unless($client->user_id === auth()->id(), 403);

        return view('app.clients.show', compact('client'));
    }

    public function edit(Client $client): View
    {
        abort_unless($client->user_id === auth()->id(), 403);

        return view('app.clients.edit', compact('client'));
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
