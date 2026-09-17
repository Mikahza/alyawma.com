<script setup lang="ts">
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

/**
 * This project's select, wrapping the shadcn primitive.
 *
 * The hidden input is what makes it usable inside Inertia's `<Form>`: that
 * component serialises native form fields, and a Reka dropdown is not one — it
 * is a button and a popover. Outside a form the hidden input simply sits there,
 * so this works anywhere a plain `<select>` would.
 *
 * Reka reads an option's label from the DOM once, when the item mounts, so a
 * label that changes language in place would keep showing the old one. Keying
 * on the label remounts the item and re-registers it.
 */
type Option = {
    value: string;
    label: string;
};

defineProps<{
    id?: string;
    name: string;
    options: Option[];
    placeholder?: string;
    class?: string;
}>();

const model = defineModel<string>({ required: true });
</script>

<template>
    <div :class="$props.class">
        <Select v-model="model">
            <SelectTrigger :id="id" class="w-full">
                <SelectValue :placeholder="placeholder" />
            </SelectTrigger>

            <SelectContent>
                <SelectItem
                    v-for="option in options"
                    :key="`${option.value}:${option.label}`"
                    :value="option.value"
                >
                    {{ option.label }}
                </SelectItem>
            </SelectContent>
        </Select>

        <input type="hidden" :name="name" :value="model" />
    </div>
</template>
