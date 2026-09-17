<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import SegmentedControl from '@/components/shared/SegmentedControl.vue';
import { t } from '@/plugins/i18n';

/**
 * The language is switched the way the theme is: a cookie the server reads on
 * the next request. Reloading rather than swapping the catalogue client-side
 * means everything the server produced — validation messages, flash toasts,
 * page titles — comes back in the new language too, not just the templates.
 */
const labels: Record<string, string> = {
    fr: 'Français',
    en: 'English',
};

const page = usePage();

const options = computed(() =>
    page.props.locales.map((locale) => ({
        value: locale,
        label: labels[locale] ?? locale,
    })),
);

const selected = computed({
    get: () => page.props.locale,
    set: (locale: string) => {
        if (locale === page.props.locale) {
            return;
        }

        document.cookie = `locale=${locale};path=/;max-age=${365 * 24 * 60 * 60};SameSite=Lax`;

        router.reload();
    },
});
</script>

<template>
    <SegmentedControl
        v-model="selected"
        :options="options"
        :label="t('settings.language')"
    />
</template>
