<?php

use App\Enums\SignatureStatus;
use App\Models\Client;
use App\Models\Signature;
use App\Models\Term;
use App\Models\TermVersion;
use App\Mail\SignatureLinkMail;
use App\Mail\SignedDocumentMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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

test('client can sign a document, a pdf is generated, and the client is emailed', function () {
    Storage::fake('local');
    Mail::fake();

    $client  = Client::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.ie']);
    $term    = Term::factory()->create(['body' => 'Hello {{CLIENT_NAME}}.']);
    $version = TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $sig     = Signature::create(['client_id' => $client->id, 'term_version_id' => $version->id]);

    // Case-insensitive input is accepted.
    $this->post(route('sign.sign', $sig), ['signed_name' => 'jane smith'])
        ->assertRedirect(route('sign.show', $sig));

    $sig->refresh();
    expect($sig->status)->toBe(SignatureStatus::Signed);
    expect($sig->signed_name)->toBe('Jane Smith'); // canonical name stored
    expect($sig->content_hash)->not->toBeNull();
    expect($sig->pdf_path)->not->toBeNull();
    expect($sig->pdf_hash)->not->toBeNull();
    expect($sig->pdf_hash)->toMatch('/^[0-9a-f]{64}$/');
    Storage::disk('local')->assertExists($sig->pdf_path);
    Mail::assertQueued(SignedDocumentMail::class);
});

// ── Expiry ────────────────────────────────────────────────────────────────

test('a signature expires 7 days after assignment by default', function () {
    $client  = Client::factory()->create();
    $term    = Term::factory()->create();
    $version = TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $sig     = Signature::create(['client_id' => $client->id, 'term_version_id' => $version->id]);

    expect($sig->expires_at->isBetween(now()->addDays(6), now()->addDays(8)))->toBeTrue();
    expect($sig->isExpired())->toBeFalse();
});

test('an expired signing link cannot be signed and shows an expired state', function () {
    Storage::fake('local');

    $client  = Client::factory()->create(['name' => 'Jane Smith']);
    $term    = Term::factory()->create(['body' => 'Hello {{CLIENT_NAME}}.']);
    $version = TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $sig     = Signature::create([
        'client_id'       => $client->id,
        'term_version_id' => $version->id,
        'expires_at'      => now()->subDay(),
    ]);

    $this->get(route('sign.show', $sig))
        ->assertInertia(fn (Assert $page) => $page->where('signature.is_expired', true));

    $this->post(route('sign.sign', $sig), ['signed_name' => 'Jane Smith'])
        ->assertForbidden();

    expect($sig->fresh()->isPending())->toBeTrue();
});

// ── Send link via email (owner opt-in) ────────────────────────────────────

test('owner can email the signing link to the client', function () {
    Mail::fake();

    $user   = User::factory()->create();
    $client = Client::factory()->for($user)->create();
    $term   = Term::factory()->for($user)->create();
    $ver    = TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $sig    = Signature::create(['client_id' => $client->id, 'term_version_id' => $ver->id]);

    $this->actingAs($user)
        ->post(route('app.signatures.send', $sig))
        ->assertRedirect();

    Mail::assertQueued(SignatureLinkMail::class);
});

test('another user cannot email a signing link', function () {
    $client = Client::factory()->create();
    $term   = Term::factory()->create();
    $ver    = TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $sig    = Signature::create(['client_id' => $client->id, 'term_version_id' => $ver->id]);

    $this->actingAs(User::factory()->create())
        ->post(route('app.signatures.send', $sig))
        ->assertForbidden();
});

// ── Extend expiry ─────────────────────────────────────────────────────────

test('owner can extend an expired signing link', function () {
    $user   = User::factory()->create();
    $client = Client::factory()->for($user)->create();
    $term   = Term::factory()->for($user)->create();
    $ver    = TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $sig    = Signature::create([
        'client_id'       => $client->id,
        'term_version_id' => $ver->id,
        'expires_at'      => now()->subDay(),
    ]);

    $this->actingAs($user)
        ->post(route('app.signatures.extend', $sig))
        ->assertRedirect();

    $sig->refresh();
    expect($sig->isExpired())->toBeFalse();
    expect($sig->expires_at->isFuture())->toBeTrue();
});

test('another user cannot extend a signing link', function () {
    $sig = Signature::create([
        'client_id'       => Client::factory()->create()->id,
        'term_version_id' => TermVersion::factory()->create(['version' => 1])->id,
        'expires_at'      => now()->subDay(),
    ]);

    $this->actingAs(User::factory()->create())
        ->post(route('app.signatures.extend', $sig))
        ->assertForbidden();
});

test('a signed signature cannot be extended', function () {
    $user = User::factory()->create();
    $client = Client::factory()->for($user)->create();
    $term = Term::factory()->for($user)->create();
    $ver = TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $sig = Signature::create([
        'client_id'       => $client->id,
        'term_version_id' => $ver->id,
        'status'          => SignatureStatus::Signed,
        'signed_name'     => 'Jane',
        'signed_at'       => now(),
    ]);

    $this->actingAs($user)
        ->post(route('app.signatures.extend', $sig))
        ->assertForbidden();
});

test('signing is rejected when the name does not match the client', function () {
    Storage::fake('local');

    $client  = Client::factory()->create(['name' => 'Jane Smith']);
    $term    = Term::factory()->create(['body' => 'Hello {{CLIENT_NAME}}.']);
    $version = TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $sig     = Signature::create(['client_id' => $client->id, 'term_version_id' => $version->id]);

    $this->post(route('sign.sign', $sig), ['signed_name' => 'Someone Else'])
        ->assertSessionHasErrors('signed_name');

    expect($sig->fresh()->status)->toBe(SignatureStatus::Pending);
});

test('signed pdf can be downloaded', function () {
    Storage::fake('local');

    $client  = Client::factory()->create(['name' => 'Jane Smith']);
    $term    = Term::factory()->create(['body' => 'Hello {{CLIENT_NAME}}.']);
    $version = TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $sig     = Signature::create(['client_id' => $client->id, 'term_version_id' => $version->id]);

    $this->post(route('sign.sign', $sig), ['signed_name' => 'Jane Smith']);

    $this->get(route('sign.pdf', $sig))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');
});

test('pdf download is forbidden for a pending signature', function () {
    $client  = Client::factory()->create();
    $term    = Term::factory()->create();
    $version = TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $sig     = Signature::create(['client_id' => $client->id, 'term_version_id' => $version->id]);

    $this->get(route('sign.pdf', $sig))->assertForbidden();
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
