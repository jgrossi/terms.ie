<?php

use Inertia\Testing\AssertableInertia as Assert;

test('terms of service page renders', function () {
    $this->get(route('legal.terms'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('legal/Terms'));
});

test('privacy policy page renders', function () {
    $this->get(route('legal.privacy'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('legal/Privacy'));
});
