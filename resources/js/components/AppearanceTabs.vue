<script setup lang="ts">
import { Monitor, Moon, Sun } from '@lucide/vue';
import { computed } from 'vue';
import SegmentedControl from '@/components/shared/SegmentedControl.vue';
import type { Appearance } from '@/composables/useAppearance';
import { useAppearance } from '@/composables/useAppearance';
import { t } from '@/plugins/i18n';

const { appearance, updateAppearance } = useAppearance();

const options = computed(() => [
    { value: 'light' as Appearance, label: t('settings.light'), icon: Sun },
    { value: 'dark' as Appearance, label: t('settings.dark'), icon: Moon },
    {
        value: 'system' as Appearance,
        label: t('settings.system'),
        icon: Monitor,
    },
]);

const selected = computed({
    get: () => appearance.value,
    set: updateAppearance,
});
</script>

<template>
    <SegmentedControl
        v-model="selected"
        :options="options"
        :label="t('settings.appearance')"
    />
</template>
