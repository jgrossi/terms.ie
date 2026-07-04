<?php

use App\Mail\MagicLinkMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

test('sending a magic link to a new email creates the user and emails the link', function () {
    Mail::fake();

    $this->post(route('magic-link.send'), ['email' => 'new@example.ie'])
        ->assertRedirect(route('home'));

    expect(User::where('email', 'new@example.ie')->exists())->toBeTrue();
    Mail::assertSent(MagicLinkMail::class);
});

test('magic link requests are rate limited per email', function () {
    Mail::fake();

    foreach (range(1, 5) as $i) {
        $this->post(route('magic-link.send'), ['email' => 'spam@example.ie']);
    }

    $this->post(route('magic-link.send'), ['email' => 'spam@example.ie'])
        ->assertStatus(429);
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
