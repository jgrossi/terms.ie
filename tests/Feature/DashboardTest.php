<?php

use App\Enums\SignatureStatus;
use App\Models\Client;
use App\Models\Signature;
use App\Models\Term;
use App\Models\TermVersion;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guest is redirected from the dashboard', function () {
    $this->get(route('app.dashboard'))->assertRedirect();
});

test('dashboard shows the user\'s counts and pending signatures', function () {
    $user   = User::factory()->create();
    $term   = Term::factory()->for($user)->create();
    $ver    = TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $client = Client::factory()->for($user)->create();
    Signature::create(['client_id' => $client->id, 'term_version_id' => $ver->id]);

    $this->actingAs($user)
        ->get(route('app.dashboard'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->where('termCount', 1)
            ->where('clientCount', 1)
            ->where('pendingCount', 1)
            ->where('signedCount', 0)
            ->has('pendingSignatures', 1)
        );
});

test('dashboard does not count another user\'s data', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    Term::factory()->for($other)->create();
    Client::factory()->for($other)->create();

    $this->actingAs($user)
        ->get(route('app.dashboard'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('termCount', 0)
            ->where('clientCount', 0)
        );
});
