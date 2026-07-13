<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\Auth\MagicLinkController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\SignController;
use App\Http\Controllers\VerifyController;
use App\Http\Controllers\TermController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Home'))->name('home');
Route::get('/terms', fn () => Inertia::render('legal/Terms'))->name('legal.terms');
Route::get('/privacy', fn () => Inertia::render('legal/Privacy'))->name('legal.privacy');

Route::post('/magic-link', [MagicLinkController::class, 'send'])
    ->middleware('throttle:magic-link')
    ->name('magic-link.send');
Route::get('/magic-link/verify/{user}', [MagicLinkController::class, 'verify'])->middleware('signed')->name('magic-link.verify');
Route::post('/logout', [MagicLinkController::class, 'logout'])->name('logout');

Route::get('/sign/{signature}', [SignController::class, 'show'])->name('sign.show');
Route::post('/sign/{signature}', [SignController::class, 'sign'])->name('sign.sign');
Route::get('/sign/{signature}/pdf', [SignController::class, 'pdf'])->name('sign.pdf');
Route::get('/verify/{signature}', [VerifyController::class, 'show'])->name('verify.show');
Route::post('/verify/{signature}', [VerifyController::class, 'verify'])->name('verify.verify');

Route::prefix('app')->name('app.')->middleware('auth')->scopeBindings()->group(function () {
    Route::get('/', AppController::class)->name('dashboard');
    Route::resource('terms', TermController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::resource('clients', ClientController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::get('terms/{term}/signatures/create', [SignatureController::class, 'create'])->name('signatures.create');
    Route::post('terms/{term}/signatures', [SignatureController::class, 'store'])->name('signatures.store');
    Route::get('clients/{client}/signatures/create', [SignatureController::class, 'createForClient'])->name('signatures.create-for-client');
    Route::post('clients/{client}/signatures', [SignatureController::class, 'storeForClient'])->name('signatures.store-for-client');
    Route::get('signatures/{signature}', [SignatureController::class, 'show'])->name('signatures.show');
    Route::post('signatures/{signature}/send', [SignatureController::class, 'send'])->name('signatures.send');
    Route::post('signatures/{signature}/extend', [SignatureController::class, 'extend'])->name('signatures.extend');
    Route::delete('signatures/{signature}', [SignatureController::class, 'destroy'])->name('signatures.destroy');
});

if (app()->environment('local')) {
    Route::get('/dev/login', function () {
        $user = \App\Models\User::firstOrCreate(['email' => 'junior@grossi.ie']);
        auth()->login($user, remember: true);
        return redirect()->route('app.dashboard');
    })->name('dev.login');
}
