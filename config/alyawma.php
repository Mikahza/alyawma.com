<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Administrator account
    |--------------------------------------------------------------------------
    |
    | The single administrator account, seeded on every deployment. It is the
    | only account allowed to publish foods to the shared catalogue. Both the
    | address and the password must be set, or no administrator is seeded at
    | all — this is what keeps a guessable account out of production.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Locales
    |--------------------------------------------------------------------------
    |
    | The languages the interface is served in. The first is the default, and
    | `app.locale` should agree with it. Adding one means adding a catalogue
    | under `resources/js/locales/` and a directory under `lang/` — the parity
    | test refuses a catalogue that drifts from the others.
    |
    */

    'locales' => ['fr', 'en'],

    'administrator' => [
        'name' => env('ADMIN_NAME', 'Administrator'),
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWORD'),
    ],

];
