<?php

use App\Enums\SignatureStatus;
use App\Models\Client;
use App\Models\Signature;
use App\Models\Term;
use App\Models\TermVersion;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

// ── Assign (create / store) ───────────────────────────────────────────────

test('owner can view the assign form', function () {
    $user = User::factory()->create();
    $term = Term::factory()->for($user)->create();
    TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);

    $this->actingAs($user)
        ->get(route('app.signatures.create', $term))
        ->assertInertia(fn (Assert $page) => $page
            ->component('signatures/Create')
            ->where('term.id', $term->id)
        );
});

test('another user cannot access the assign form', function () {
    $term = Term::factory()->create();
    TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);

    $this->actingAs(User::factory()->create())
        ->get(route('app.signatures.create', $term))
        ->assertForbidden();
});

test('assigning a term creates a pending signature', function () {
    $user   = User::factory()->create();
    $term   = Term::factory()->for($user)->create(['body' => 'Hello {{CLIENT_NAME}}.']);
    TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $client = Client::factory()->for($user)->create();

    $this->actingAs($user)
        ->post(route('app.signatures.store', $term), ['client_id' => $client->id])
        ->assertRedirect();

    $sig = Signature::first();
    expect($sig)->not->toBeNull();
    expect($sig->status)->toBe(SignatureStatus::Pending);
    expect($sig->client_id)->toBe($client->id);
});

test('cannot assign a term using another user\'s client', function () {
    $user        = User::factory()->create();
    $term        = Term::factory()->for($user)->create();
    TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $otherClient = Client::factory()->create();

    $this->actingAs($user)
        ->post(route('app.signatures.store', $term), ['client_id' => $otherClient->id])
        ->assertSessionHasErrors('client_id');
});

test('user variables are required when storing a signature', function () {
    $user   = User::factory()->create();
    $term   = Term::factory()->for($user)->create(['body' => 'Date: {{DATE}}.']);
    TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $client = Client::factory()->for($user)->create();

    $this->actingAs($user)
        ->post(route('app.signatures.store', $term), ['client_id' => $client->id])
        ->assertSessionHasErrors('variables.DATE');
});

// ── Assign from client (client-first flow) ────────────────────────────────

test('owner can view the assign form from a client', function () {
    $user   = User::factory()->create();
    $client = Client::factory()->for($user)->create();

    $this->actingAs($user)
        ->get(route('app.signatures.create-for-client', $client))
        ->assertInertia(fn (Assert $page) => $page
            ->component('signatures/CreateForClient')
            ->where('client.id', $client->id)
        );
});

test('another user cannot access the client assign form', function () {
    $client = Client::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('app.signatures.create-for-client', $client))
        ->assertForbidden();
});

test('assigning from a client creates a pending signature', function () {
    $user    = User::factory()->create();
    $client  = Client::factory()->for($user)->create();
    $term    = Term::factory()->for($user)->create(['body' => 'Hello {{CLIENT_NAME}}.']);
    TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);

    $this->actingAs($user)
        ->post(route('app.signatures.store-for-client', $client), ['term_id' => $term->id])
        ->assertRedirect();

    $sig = Signature::first();
    expect($sig)->not->toBeNull();
    expect($sig->status)->toBe(SignatureStatus::Pending);
    expect($sig->client_id)->toBe($client->id);
});

test('cannot assign another user\'s term from a client', function () {
    $user   = User::factory()->create();
    $client = Client::factory()->for($user)->create();
    $otherTerm = Term::factory()->create(['body' => 'Hello.']);
    TermVersion::factory()->for($otherTerm)->create(['version' => 1, 'body' => $otherTerm->body]);

    $this->actingAs($user)
        ->post(route('app.signatures.store-for-client', $client), ['term_id' => $otherTerm->id])
        ->assertSessionHasErrors('term_id');
});

// ── Admin show ────────────────────────────────────────────────────────────

test('owner can view their signature', function () {
    $user    = User::factory()->create();
    $client  = Client::factory()->for($user)->create();
    $term    = Term::factory()->for($user)->create();
    $version = TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $sig     = Signature::create(['client_id' => $client->id, 'term_version_id' => $version->id]);

    $this->actingAs($user)
        ->get(route('app.signatures.show', $sig))
        ->assertOk();
});

test('another user cannot view a signature', function () {
    $client  = Client::factory()->create();
    $term    = Term::factory()->create();
    $version = TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $sig     = Signature::create(['client_id' => $client->id, 'term_version_id' => $version->id]);

    $this->actingAs(User::factory()->create())
        ->get(route('app.signatures.show', $sig))
        ->assertForbidden();
});

// ── Revoke ────────────────────────────────────────────────────────────────

test('owner can revoke a pending signature', function () {
    $user    = User::factory()->create();
    $client  = Client::factory()->for($user)->create();
    $term    = Term::factory()->for($user)->create();
    $version = TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $sig     = Signature::create(['client_id' => $client->id, 'term_version_id' => $version->id]);

    $this->actingAs($user)
        ->delete(route('app.signatures.destroy', $sig))
        ->assertRedirect(route('app.terms.show', $term));

    expect(Signature::find($sig->id))->toBeNull();
});

test('cannot revoke an already signed signature', function () {
    $user    = User::factory()->create();
    $client  = Client::factory()->for($user)->create();
    $term    = Term::factory()->for($user)->create();
    $version = TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $sig     = Signature::create([
        'client_id'       => $client->id,
        'term_version_id' => $version->id,
        'status'          => SignatureStatus::Signed,
        'signed_name'     => 'Jane',
        'signed_at'       => now(),
    ]);

    $this->actingAs($user)
        ->delete(route('app.signatures.destroy', $sig))
        ->assertForbidden();
});

// ── Public signing ────────────────────────────────────────────────────────

test('client can view the public signing page', function () {
    $client  = Client::factory()->create();
    $term    = Term::factory()->create(['body' => 'Hello {{CLIENT_NAME}}.']);
    $version = TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $sig     = Signature::create(['client_id' => $client->id, 'term_version_id' => $version->id]);

    $this->get(route('sign.show', $sig))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('sign/Show')
            ->where('signature.is_signed', false)
        );
});

test('client can sign a document', function () {
    $client  = Client::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.ie']);
    $term    = Term::factory()->create(['body' => 'Hello {{CLIENT_NAME}}.']);
    $version = TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $sig     = Signature::create(['client_id' => $client->id, 'term_version_id' => $version->id]);

    $this->post(route('sign.sign', $sig), ['signed_name' => 'Jane Smith'])
        ->assertRedirect(route('sign.show', $sig));

    $sig->refresh();
    expect($sig->status)->toBe(SignatureStatus::Signed);
    expect($sig->signed_name)->toBe('Jane Smith');
    expect($sig->content_hash)->not->toBeNull();
});

test('cannot sign an already signed document', function () {
    $client  = Client::factory()->create();
    $term    = Term::factory()->create();
    $version = TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $sig     = Signature::create([
        'client_id'       => $client->id,
        'term_version_id' => $version->id,
        'status'          => SignatureStatus::Signed,
        'signed_name'     => 'Jane',
        'signed_at'       => now(),
    ]);

    $this->post(route('sign.sign', $sig), ['signed_name' => 'Jane Smith'])
        ->assertForbidden();
});
