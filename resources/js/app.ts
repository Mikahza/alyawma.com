import { createInertiaApp, router } from '@inertiajs/vue3';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';
import i18n, { setLocale } from '@/plugins/i18n';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

void createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    // The server decides the language, from the `locale` cookie. Reading it from
    // the first page object means the interface renders translated straight
    // away, with no flash of the fallback language.
    withApp(app, { page }) {
        setLocale(page.props.locale);
        app.use(i18n);
    },
    layout: (name) => {
        switch (true) {
            case name === 'Welcome':
                return null;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                return AppLayout;
        }
    },
    progress: {
        color: '#4B5563',
    },
});

// `withApp` runs once, at boot, so the language switcher — which reloads
// through Inertia rather than the browser — would leave the client catalogue
// behind: the server would answer in the new language while the templates
// stayed in the old one. Following the shared prop on every visit keeps the
// two in step, back and forward navigation included.
router.on('success', (event) => {
    setLocale(event.detail.page.props.locale);
});

// This will set light / dark mode on page load...
initializeTheme();

// This will listen for flash toast data from the server...
initializeFlashToast();
