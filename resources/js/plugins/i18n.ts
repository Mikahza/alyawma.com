import { createI18n } from 'vue-i18n';
import en from '@/locales/en';
import fr from '@/locales/fr';

/**
 * A single instance, created at module load.
 *
 * `defineOptions` is hoisted out of `setup()`, so a page declaring translated
 * breadcrumbs cannot reach `useI18n()`. Exporting the instance gives those
 * call sites a `t` that works outside a component too.
 *
 * Both catalogues are bundled rather than fetched on demand: they weigh a few
 * kilobytes against a bundle of a few hundred, and a language that arrives
 * after a round trip reads as a bug. Revisit if they grow by an order of
 * magnitude.
 */
const i18n = createI18n({
    legacy: false,
    locale: 'fr',
    // A key missing from one catalogue falls back to French rather than showing
    // its own name. The parity test is what should catch it first, at build time.
    fallbackLocale: 'fr',
    messages: { en, fr },
});

export default i18n;

/** Translate outside a component — breadcrumbs, page titles, plain modules. */
export const t = i18n.global.t;

export function setLocale(locale: string): void {
    i18n.global.locale.value = locale as 'en' | 'fr';
}
