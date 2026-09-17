<script setup lang="ts" generic="T extends string">
import type { LucideIcon } from '@lucide/vue';

/**
 * A row of mutually exclusive choices, sized for a thumb.
 *
 * Used wherever a setting has three or four options and the whole set is worth
 * showing at once — a select would hide the alternatives behind a tap.
 */
type Option = {
    value: T;
    label: string;
    icon?: LucideIcon;
};

defineProps<{
    options: Option[];
    /** Names the group for assistive technology, since the buttons alone do not. */
    label: string;
}>();

const model = defineModel<T>({ required: true });
</script>

<template>
    <div
        class="inline-flex gap-1 rounded-lg bg-neutral-100 p-1 dark:bg-neutral-800"
        role="group"
        :aria-label="label"
    >
        <button
            v-for="option in options"
            :key="option.value"
            type="button"
            :aria-pressed="option.value === model"
            :class="[
                'flex items-center rounded-md px-3.5 py-1.5 transition-colors',
                option.value === model
                    ? 'bg-white shadow-xs dark:bg-neutral-700 dark:text-neutral-100'
                    : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60',
            ]"
            @click="model = option.value"
        >
            <component
                :is="option.icon"
                v-if="option.icon"
                class="-ml-1 h-4 w-4"
            />
            <span :class="['text-sm', option.icon && 'ml-1.5']">{{
                option.label
            }}</span>
        </button>
    </div>
</template>
