<?php

use App\Models\User;
use Illuminate\Support\Facades\URL;

test('sending a magic link to a new email creates the user and redirects home', function () {
    $this->post(route('magic-link.send'), ['email' => 'new@example.ie'])
        ->assertRedirect(route('home'));

    expect(User::where('email', 'new@example.ie')->exists())->toBeTrue();
});

test('sending a magic link to an existing email does not duplicate the user', function () {
    User::factory()->create(['email' => 'existing@example.ie']);

    $this->post(route('magic-link.send'), ['email' => 'existing@example.ie'])
        ->assertRedirect(route('home'));

    expect(User::where('email', 'existing@example.ie')->count())->toBe(1);
});

test('magic link send validates the email', function () {
    $this->post(route('magic-link.send'), ['email' => 'not-an-email'])
        ->assertSessionHasErrors('email');
});

test('valid magic link logs the user in and redirects to dashboard', function () {
    $user = User::factory()->create();

    $url = URL::temporarySignedRoute('magic-link.verify', now()->addMinutes(15), ['user' => $user->id]);

    $this->get($url)
        ->assertRedirect(route('app.dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('expired magic link returns 403', function () {
    $user = User::factory()->create();

    $url = URL::temporarySignedRoute('magic-link.verify', now()->subMinute(), ['user' => $user->id]);

    $this->get($url)->assertForbidden();
});

test('logout clears the session and redirects home', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('logout'))
        ->assertRedirect(route('home'));

    $this->assertGuest();
});
