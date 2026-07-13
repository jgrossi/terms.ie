<?php

use App\Models\Client;
use App\Models\Signature;
use App\Models\Term;
use App\Models\TermVersion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

function signDocument(string $name = 'Jane Smith', string $body = 'Hello {{CLIENT_NAME}}.'): Signature
{
    $client  = Client::factory()->create(['name' => $name]);
    $term    = Term::factory()->create(['body' => $body]);
    $version = TermVersion::factory()->for($term)->create(['version' => 1, 'body' => $term->body]);
    $sig     = Signature::create(['client_id' => $client->id, 'term_version_id' => $version->id]);

    test()->post(route('sign.sign', $sig), ['signed_name' => $name]);

    return $sig->refresh();
}

beforeEach(function () {
    Storage::fake('local');
});

// ── Page render ───────────────────────────────────────────────────────────

test('the verify page renders for a signature', function () {
    $sig = signDocument();

    $this->get(route('verify.show', $sig))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('verify/Show')
            ->where('reference', $sig->id)
            ->where('verification', null)
        );
});

test('an unknown verification reference 404s', function () {
    $this->get(route('verify.show', '0196aaaa-bbbb-7ccc-8ddd-eeeeeeeeeeee'))->assertNotFound();
});

// ── Verification outcomes ─────────────────────────────────────────────────

test('uploading the original signed pdf verifies successfully', function () {
    $sig   = signDocument();
    $bytes = Storage::disk('local')->get($sig->pdf_path);

    $upload = UploadedFile::fake()->createWithContent('signed.pdf', $bytes);

    $this->post(route('verify.verify', $sig), ['document' => $upload])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('verify/Show')
            ->where('verification.status', 'verified')
            ->where('verification.id', $sig->id)
            ->where('verification.signed_name', 'Jane Smith')
        );
});

test('uploading another signature pdf fails the uuid check', function () {
    $sigA = signDocument(name: 'Anna', body: 'Terms for {{CLIENT_NAME}}.');
    $sigB = signDocument(name: 'Brian', body: 'Other terms for {{CLIENT_NAME}}.');

    // B's real PDF exists in the DB, but it belongs to a different reference.
    $bytesB = Storage::disk('local')->get($sigB->pdf_path);
    $upload = UploadedFile::fake()->createWithContent('signed.pdf', $bytesB);

    $this->post(route('verify.verify', $sigA), ['document' => $upload])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('verify/Show')
            ->where('verification.status', 'failed')
        );
});

test('uploading a pdf whose hash is not recorded fails', function () {
    $sig = signDocument();

    // A genuine PDF whose bytes were never stored against any signature.
    $foreign = Pdf::loadHtml('<p>not a terms.ie document</p>')->output();
    $upload  = UploadedFile::fake()->createWithContent('foreign.pdf', $foreign);

    $this->post(route('verify.verify', $sig), ['document' => $upload])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('verify/Show')
            ->where('verification.status', 'failed')
        );
});

test('a non-pdf upload is rejected', function () {
    $sig   = signDocument();
    $upload = UploadedFile::fake()->createWithContent('notes.txt', 'just text');

    $this->post(route('verify.verify', $sig), ['document' => $upload])
        ->assertSessionHasErrors('document');
});
