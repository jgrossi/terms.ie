<?php

use App\Models\Client;
use App\Models\User;

// ── Index ─────────────────────────────────────────────────────────────────

test('authenticated user sees their clients', function () {
    $user   = User::factory()->create();
    $client = Client::factory()->for($user)->create();

    $this->actingAs($user)
        ->get(route('app.clients.index'))
        ->assertOk()
        ->assertSee($client->name);
});

test('guest is redirected from clients index', function () {
    $this->get(route('app.clients.index'))->assertRedirect();
});

test('clients index does not show another user\'s clients', function () {
    $user  = User::factory()->create();
    $other = Client::factory()->create();

    $this->actingAs($user)
        ->get(route('app.clients.index'))
        ->assertOk()
        ->assertDontSee($other->name);
});

// ── Create / Store ────────────────────────────────────────────────────────

test('storing a client creates it and redirects to show', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('app.clients.store'), [
            'name'  => 'Jane Smith',
            'email' => 'jane@example.ie',
        ])
        ->assertRedirect();

    expect(Client::where('email', 'jane@example.ie')->exists())->toBeTrue();
});

test('store validates name and email are required', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('app.clients.store'), [])
        ->assertSessionHasErrors(['name', 'email']);
});

test('store validates email format', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('app.clients.store'), ['name' => 'Jane', 'email' => 'not-an-email'])
        ->assertSessionHasErrors(['email']);
});

// ── Show ──────────────────────────────────────────────────────────────────

test('owner can view their client', function () {
    $user   = User::factory()->create();
    $client = Client::factory()->for($user)->create();

    $this->actingAs($user)
        ->get(route('app.clients.show', $client))
        ->assertOk()
        ->assertSee($client->name);
});

test('another user cannot view a client', function () {
    $client = Client::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('app.clients.show', $client))
        ->assertForbidden();
});

// ── Edit / Update ─────────────────────────────────────────────────────────

test('owner can update a client', function () {
    $user   = User::factory()->create();
    $client = Client::factory()->for($user)->create();

    $this->actingAs($user)
        ->put(route('app.clients.update', $client), [
            'name'  => 'Updated Name',
            'email' => 'updated@example.ie',
        ])
        ->assertRedirect(route('app.clients.show', $client));

    expect($client->fresh()->name)->toBe('Updated Name');
});

test('another user cannot update a client', function () {
    $client = Client::factory()->create();

    $this->actingAs(User::factory()->create())
        ->put(route('app.clients.update', $client), ['name' => 'x', 'email' => 'x@x.ie'])
        ->assertForbidden();
});

// ── Destroy ───────────────────────────────────────────────────────────────

test('owner can delete a client with no terms', function () {
    $user   = User::factory()->create();
    $client = Client::factory()->for($user)->create();

    $this->actingAs($user)
        ->delete(route('app.clients.destroy', $client))
        ->assertRedirect(route('app.clients.index'));

    expect(Client::find($client->id))->toBeNull();
});

test('another user cannot delete a client', function () {
    $client = Client::factory()->create();

    $this->actingAs(User::factory()->create())
        ->delete(route('app.clients.destroy', $client))
        ->assertForbidden();
});
