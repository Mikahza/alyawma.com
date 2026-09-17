<?php

use Illuminate\Support\Arr;

/**
 * Flatten a catalogue to dotted keys so nesting differences surface too.
 *
 * @param  array<string, mixed>  $catalogue
 * @return list<string>
 */
function catalogueKeys(array $catalogue): array
{
    $keys = array_keys(Arr::dot($catalogue));
    sort($keys);

    return $keys;
}

/**
 * @return array<string, array<string, mixed>>
 */
function loadCatalogues(string $locale): array
{
    $catalogues = [];

    foreach (glob(resource_path("js/locales/{$locale}/*.json")) ?: [] as $file) {
        $catalogues[basename($file)] = json_decode(file_get_contents($file), associative: true, flags: JSON_THROW_ON_ERROR);
    }

    return $catalogues;
}

test('every locale ships the same catalogue files', function () {
    $locales = config('alyawma.locales');
    $reference = array_keys(loadCatalogues($locales[0]));

    expect($reference)->not->toBeEmpty();

    foreach (array_slice($locales, 1) as $locale) {
        expect(array_keys(loadCatalogues($locale)))
            ->toEqualCanonicalizing($reference, "The `{$locale}` catalogue is missing a file, or has one too many.");
    }
});

/**
 * The guarantee a `fallbackLocale` would otherwise buy at runtime, taken at
 * build time instead: a key that exists in one language exists in them all, so
 * nobody ever reads an untranslated string because a key was forgotten.
 */
test('every key exists in every locale', function () {
    $locales = config('alyawma.locales');
    $reference = loadCatalogues($locales[0]);

    foreach (array_slice($locales, 1) as $locale) {
        $catalogues = loadCatalogues($locale);

        foreach ($reference as $file => $contents) {
            expect(catalogueKeys($catalogues[$file] ?? []))
                ->toEqual(catalogueKeys($contents), "`{$locale}/{$file}` has drifted from `{$locales[0]}/{$file}`.");
        }
    }
});

test('no translation is left empty', function () {
    foreach (config('alyawma.locales') as $locale) {
        foreach (loadCatalogues($locale) as $file => $contents) {
            foreach (Arr::dot($contents) as $key => $value) {
                expect(trim((string) $value))->not->toBe('', "`{$locale}/{$file}` leaves `{$key}` empty.");
            }
        }
    }
});

/**
 * `@` opens a linked message and `|` separates plural forms, so either one used
 * literally throws while vue-i18n compiles the message. The throw happens during
 * render, which blanks the page — and only on the screens that use that key, so
 * it survives a casual click-through. `{'@'}` is the escaped form.
 */
test('no translation uses a vue-i18n control character literally', function () {
    foreach (config('alyawma.locales') as $locale) {
        foreach (loadCatalogues($locale) as $file => $contents) {
            foreach (Arr::dot($contents) as $key => $value) {
                $withoutEscapes = preg_replace("/\{'[^']*'\}/", '', (string) $value);

                expect(preg_match('/[@|]/', (string) $withoutEscapes))
                    ->toBe(0, "`{$locale}/{$file}` uses `@` or `|` literally in `{$key}`. Escape it as `{'@'}`.");
            }
        }
    }
});
