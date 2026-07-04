<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\Auth\MagicLinkController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\SignController;
use App\Http\Controllers\TermController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Home'))->name('home');

Route::post('/magic-link', [MagicLinkController::class, 'send'])->name('magic-link.send');
Route::get('/magic-link/verify/{user}', [MagicLinkController::class, 'verify'])->middleware('signed')->name('magic-link.verify');
Route::post('/logout', [MagicLinkController::class, 'logout'])->name('logout');

Route::get('/sign/{signature}', [SignController::class, 'show'])->name('sign.show');
Route::post('/sign/{signature}', [SignController::class, 'sign'])->name('sign.sign');

Route::prefix('app')->name('app.')->middleware('auth')->scopeBindings()->group(function () {
    Route::get('/', AppController::class)->name('dashboard');
    Route::resource('terms', TermController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::resource('clients', ClientController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::get('terms/{term}/signatures/create', [SignatureController::class, 'create'])->name('signatures.create');
    Route::post('terms/{term}/signatures', [SignatureController::class, 'store'])->name('signatures.store');
    Route::get('clients/{client}/signatures/create', [SignatureController::class, 'createForClient'])->name('signatures.create-for-client');
    Route::post('clients/{client}/signatures', [SignatureController::class, 'storeForClient'])->name('signatures.store-for-client');
    Route::get('signatures/{signature}', [SignatureController::class, 'show'])->name('signatures.show');
    Route::delete('signatures/{signature}', [SignatureController::class, 'destroy'])->name('signatures.destroy');
});

if (app()->environment('local')) {
    Route::get('/dev/login', function () {
        $user = \App\Models\User::where('email', 'junior@grossi.ie')->firstOrFail();
        auth()->login($user, remember: true);
        return redirect()->route('app.dashboard');
    })->name('dev.login');
}
