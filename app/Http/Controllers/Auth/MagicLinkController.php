<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class MagicLinkController extends Controller
{
    public function send(Request $request)
    {
        $email = $request->validate(['email' => ['required', 'email', 'max:255']])['email'];

        $user = User::firstOrCreate(['email' => $email]);

        $url = URL::temporarySignedRoute(
            'magic-link.verify',
            now()->addMinutes(15),
            ['user' => $user->id]
        );

        // TODO: Mail::to($email)->send(new MagicLinkMail($url));
        \Log::info("Magic link for {$email}: {$url}");

        return view('auth._magic-link-sent', ['email' => $email]);
    }

    public function verify(User $user)
    {
        Auth::login($user, remember: true);
        $user->update(['email_verified_at' => $user->email_verified_at ?? now()]);

        return redirect()->route('app.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
