<?php

use App\Models\User;

test('landing page renders', function () {
    $this->get(route('home'))->assertOk();
});

test('authenticated user sees go to app link', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('home'))
        ->assertSee('Go to app');
});
