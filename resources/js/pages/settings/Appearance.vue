<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import Heading from '@/components/Heading.vue';
import LocaleTabs from '@/components/shared/LocaleTabs.vue';
import { t } from '@/plugins/i18n';
import { edit } from '@/routes/appearance';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                titleKey: 'settings.appearance_title',
                href: edit(),
            },
        ],
    },
});
</script>

<template>
    <Head :title="t('settings.appearance_title')" />

    <h1 class="sr-only">{{ t('settings.appearance_title') }}</h1>

    <div class="space-y-6">
        <Heading
            variant="small"
            :title="t('settings.appearance_title')"
            :description="t('settings.appearance_subtitle')"
        />
        <AppearanceTabs />

        <Heading
            variant="small"
            :title="t('settings.language')"
            :description="t('settings.language_subtitle')"
        />
        <LocaleTabs />
    </div>
</template>
