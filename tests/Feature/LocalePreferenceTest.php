<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('the locale cookie decides the language', function () {
    $this->withUnencryptedCookie('locale', 'en')
        ->get(route('login'))
        ->assertInertia(fn (Assert $page) => $page->where('locale', 'en'));
});

test('no cookie leaves the configured default in place', function () {
    $this->get(route('login'))
        ->assertInertia(fn (Assert $page) => $page->where('locale', config('app.locale')));
});

/**
 * The cookie is unencrypted, so its value is whatever the visitor puts there.
 * Anything outside the configured list has to leave the default standing rather
 * than reach `App::setLocale()`.
 */
test('a locale the application does not serve is ignored', function (mixed $cookie) {
    $this->withUnencryptedCookie('locale', $cookie)
        ->get(route('login'))
        ->assertInertia(fn (Assert $page) => $page->where('locale', config('app.locale')));
})->with([
    'unknown language' => 'de',
    'path traversal' => '../../etc/passwd',
    'empty' => '',
]);

test('the language survives a signed-in visit', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withUnencryptedCookie('locale', 'en')
        ->get(route('foods.index'))
        ->assertInertia(fn (Assert $page) => $page->where('locale', 'en'));
});
