<?php

/*
 * The rules this application actually uses, translated.
 *
 * Laravel's catalogue is far longer; anything missing falls back to English
 * through `app.fallback_locale`. Translating rules no form can trigger would be
 * writing French nobody will ever read.
 */
return [
    'confirmed' => 'La confirmation ne correspond pas.',
    'current_password' => 'Le mot de passe est incorrect.',
    'decimal' => 'Ce champ doit comporter :decimal décimales.',
    'email' => 'Ce champ doit être une adresse e-mail valide.',
    'gt' => [
        'numeric' => 'Ce champ doit être supérieur à :value.',
    ],
    'integer' => 'Ce champ doit être un nombre entier.',
    'max' => [
        'numeric' => 'Ce champ ne peut pas dépasser :max.',
        'string' => 'Ce champ ne peut pas dépasser :max caractères.',
    ],
    'min' => [
        'numeric' => 'Ce champ doit être au moins :min.',
        'string' => 'Ce champ doit comporter au moins :min caractères.',
    ],
    'numeric' => 'Ce champ doit être un nombre.',
    'required' => 'Ce champ est obligatoire.',
    'string' => 'Ce champ doit être du texte.',
    'unique' => 'Cette valeur est déjà utilisée.',

    /*
     * Field names as they appear in a message. Without these, a French error
     * reads "Le champ carbohydrate grams est obligatoire".
     */
    'attributes' => [
        'calories' => 'calories',
        'carbohydrate_grams' => 'glucides',
        'current_password' => 'mot de passe actuel',
        'email' => 'adresse e-mail',
        'fat_grams' => 'lipides',
        'fibre_grams' => 'fibres',
        'name' => 'nom',
        'password' => 'mot de passe',
        'protein_grams' => 'protéines',
        'reference_quantity' => 'quantité de référence',
        'reference_unit' => 'unité de référence',
    ],
];
