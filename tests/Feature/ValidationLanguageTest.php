<?php

use App\Models\User;

/**
 * `lang/fr/validation.php` deliberately translates only the rules this
 * application uses, so the first rule to reach a form without a French message
 * falls back to English — silently, and only on the screen that trips it.
 *
 * Every message Laravel ships opens with "The ", which is what this looks for:
 * a form answering a French request in English.
 */
test('a form answers a French request in French', function (string $route, string $method, array $payload) {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withUnencryptedCookie('locale', 'fr')
        ->{$method}(route($route), $payload)
        ->assertSessionHasErrors();

    foreach (session('errors')->all() as $message) {
        expect($message)->not->toStartWith(
            'The ',
            "`{$route}` answered in English: \"{$message}\". Add the rule to `lang/fr/validation.php`.",
        );
    }
})->with([
    'creating a food' => ['foods.store', 'post', [
        'name' => '',
        'reference_quantity' => 'not a number',
        'reference_unit' => 'kg',
        'protein_grams' => '1.234',
        'fibre_grams' => -1,
        'calories' => 'plenty',
    ]],
    'updating the profile' => ['profile.update', 'patch', [
        'name' => '',
        'email' => 'not an address',
    ]],
    'updating the password' => ['user-password.update', 'put', [
        'current_password' => 'wrong',
        'password' => 'short',
        'password_confirmation' => 'mismatched',
    ]],
]);
