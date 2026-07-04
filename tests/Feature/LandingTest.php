<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('landing page renders', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('Home'));
});

test('authenticated user is exposed to the landing page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('home'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Home')
            ->where('auth.user.email', $user->email)
        );
});
