<?php

use App\Models\Client;
use App\Models\Signature;
use App\Models\Term;
use App\Models\TermVersion;
use App\Models\User;

// ── Index ─────────────────────────────────────────────────────────────────

test('authenticated user sees their terms index', function () {
    $user = User::factory()->create();
    $term = Term::factory()->for($user)->create();

    $this->actingAs($user)
        ->get(route('app.terms.index'))
        ->assertOk()
        ->assertSee($term->name);
});

test('guest is redirected from terms index', function () {
    $this->get(route('app.terms.index'))->assertRedirect();
});

test('terms index does not show another user\'s terms', function () {
    $user  = User::factory()->create();
    $other = Term::factory()->create();

    $this->actingAs($user)
        ->get(route('app.terms.index'))
        ->assertOk()
        ->assertDontSee($other->name);
});

// ── Create / Store ────────────────────────────────────────────────────────

test('create form renders for authenticated user', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('app.terms.create'))
        ->assertOk();
});

test('storing a term creates it with version 1', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('app.terms.store'), [
            'name' => 'Photography Terms',
            'body' => 'Hello {{CLIENT_NAME}}, welcome.',
        ])
        ->assertRedirect();

    $term = Term::where('name', 'Photography Terms')->first();
    expect($term)->not->toBeNull();
    expect($term->versions()->count())->toBe(1);
    expect($term->versions()->first()->version)->toBe(1);
});

test('store validates name and body are required', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('app.terms.store'), [])
        ->assertSessionHasErrors(['name', 'body']);
});

// ── Show ──────────────────────────────────────────────────────────────────

test('owner can view their term', function () {
    $user = User::factory()->create();
    $term = Term::factory()->for($user)->create();
    TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);

    $this->actingAs($user)
        ->get(route('app.terms.show', $term))
        ->assertOk()
        ->assertSee($term->name);
});

test('another user cannot view a term', function () {
    $term = Term::factory()->create();
    TermVersion::factory()->for($term)->create();

    $this->actingAs(User::factory()->create())
        ->get(route('app.terms.show', $term))
        ->assertForbidden();
});

// ── Edit / Update ─────────────────────────────────────────────────────────

test('owner can view the edit form', function () {
    $user = User::factory()->create();
    $term = Term::factory()->for($user)->create();

    $this->actingAs($user)
        ->get(route('app.terms.edit', $term))
        ->assertOk();
});

test('another user cannot access the edit form', function () {
    $term = Term::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('app.terms.edit', $term))
        ->assertForbidden();
});

test('updating only the name does not create a new version', function () {
    $user = User::factory()->create();
    $term = Term::factory()->for($user)->create();
    TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);

    $this->actingAs($user)
        ->put(route('app.terms.update', $term), [
            'name' => 'New Name',
            'body' => $term->body,
        ]);

    expect($term->fresh()->versions()->count())->toBe(1);
    expect($term->fresh()->name)->toBe('New Name');
});

test('updating the body creates a new version', function () {
    $user = User::factory()->create();
    $term = Term::factory()->for($user)->create();
    TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);

    $this->actingAs($user)
        ->put(route('app.terms.update', $term), [
            'name' => $term->name,
            'body' => 'Completely new body content.',
        ]);

    expect($term->fresh()->versions()->count())->toBe(2);
    expect($term->fresh()->versions()->max('version'))->toBe(2);
});

test('another user cannot update a term', function () {
    $term = Term::factory()->create();

    $this->actingAs(User::factory()->create())
        ->put(route('app.terms.update', $term), ['name' => 'x', 'body' => 'y'])
        ->assertForbidden();
});

// ── Destroy ───────────────────────────────────────────────────────────────

test('owner can delete a term with no client terms', function () {
    $user = User::factory()->create();
    $term = Term::factory()->for($user)->create();

    $this->actingAs($user)
        ->delete(route('app.terms.destroy', $term))
        ->assertRedirect(route('app.terms.index'));

    expect(Term::find($term->id))->toBeNull();
});

test('cannot delete a term that has been assigned to a client', function () {
    $user    = User::factory()->create();
    $term    = Term::factory()->for($user)->create();
    $version = TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $client  = Client::factory()->for($user)->create();

    Signature::create([
        'client_id'       => $client->id,
        'term_version_id' => $version->id,
    ]);

    $this->actingAs($user)
        ->delete(route('app.terms.destroy', $term))
        ->assertForbidden();

    expect(Term::find($term->id))->not->toBeNull();
});

test('another user cannot delete a term', function () {
    $term = Term::factory()->create();

    $this->actingAs(User::factory()->create())
        ->delete(route('app.terms.destroy', $term))
        ->assertForbidden();
});
