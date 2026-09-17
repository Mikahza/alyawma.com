<script setup lang="ts">
import type { LabelProps } from 'reka-ui';
import type { HTMLAttributes } from 'vue';
import { Label as BaseLabel } from '@/components/ui/label';

/**
 * This project's label, wrapping the shadcn primitive to add the required mark.
 *
 * The asterisk is `aria-hidden`: the field itself carries the `required`
 * attribute, which is what assistive technology announces. Repeating it as a
 * lone star would only add noise for someone using a screen reader.
 */
defineProps<
    LabelProps & {
        class?: HTMLAttributes['class'];
        required?: boolean;
    }
>();
</script>

<template>
    <BaseLabel :for="$props.for" :class="$props.class">
        <span>
            <slot />
            <span
                v-if="required"
                class="text-destructive ml-0.5"
                aria-hidden="true"
                >*</span
            >
        </span>
    </BaseLabel>
</template>
