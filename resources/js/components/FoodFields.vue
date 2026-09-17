<script setup lang="ts">
import { t } from '@/plugins/i18n';
import { computed, ref, watch } from 'vue';
import InputError from '@/components/InputError.vue';
import Select from '@/components/shared/Select.vue';
import { Input } from '@/components/ui/input';
import Label from '@/components/shared/Label.vue';
import type { Food, FoodUnit } from '@/types';

const props = defineProps<{
    food?: Food;
    errors: Record<string, string>;
}>();

const units = computed<{ value: FoodUnit; label: string }[]>(() => [
    { value: 'g', label: t('foods.unit_g') },
    { value: 'ml', label: t('foods.unit_ml') },
    { value: 'piece', label: t('foods.unit_piece') },
]);

const unit = ref<string>(props.food?.reference_unit ?? 'g');
const quantity = ref(props.food?.reference_quantity ?? '100');
const protein = ref(props.food?.protein_grams ?? '');
const fibre = ref(props.food?.fibre_grams ?? '');
const fat = ref(props.food?.fat_grams ?? '');
const carbohydrate = ref(props.food?.carbohydrate_grams ?? '');
const calories = ref(props.food ? String(props.food.calories) : '');

// An existing food already has a figure that somebody chose. Never overwrite it.
const caloriesAreOwned = ref(Boolean(props.food));

// A countable food is described one at a time, a weighed one per 100. Only on
// creation: never overwrite what an existing food already says.
watch(unit, (value) => {
    if (!props.food) {
        quantity.value = value === 'piece' ? '1' : '100';
    }
});

function toNumber(value: string): number {
    const parsed = Number.parseFloat(value);

    return Number.isFinite(parsed) ? parsed : 0;
}

// Roughly 4 kcal per gram of protein and of carbohydrate, 9 per gram of fat.
// Fibre is left out: labels disagree on whether it counts, and this only needs
// to be close enough to be useful.
const suggestedCalories = computed(() =>
    Math.round(
        4 * toNumber(protein.value) +
            4 * toNumber(carbohydrate.value) +
            9 * toNumber(fat.value),
    ),
);

// The figure follows the macros until the user types one of their own. Clearing
// the field hands it back, so there is always a way to return to the suggestion.
watch(suggestedCalories, (value) => {
    if (!caloriesAreOwned.value) {
        calories.value = value > 0 ? String(value) : '';
    }
});

function onCaloriesInput() {
    caloriesAreOwned.value = calories.value.trim() !== '';
}

// Real foods drift from the formula, so this only ever warns, and only once the
// figure is the user's own — a slipped digit, 5400 typed for 540.
const caloriesLookWrong = computed(() => {
    const entered = toNumber(calories.value);

    if (
        !caloriesAreOwned.value ||
        suggestedCalories.value < 20 ||
        entered === 0
    ) {
        return false;
    }

    return (
        Math.abs(entered - suggestedCalories.value) / suggestedCalories.value >
        0.25
    );
});
</script>

<template>
    <div class="grid gap-2">
        <Label for="name" required>{{ t('common.name') }}</Label>
        <Input
            id="name"
            name="name"
            :default-value="food?.name"
            required
            autocomplete="off"
            :placeholder="t('foods.name_placeholder')"
        />
        <InputError :message="errors.name" />
    </div>

    <div class="grid gap-2">
        <Label for="reference_quantity" required>{{
            t('foods.reference_label')
        }}</Label>
        <div class="flex gap-2">
            <Input
                id="reference_quantity"
                v-model="quantity"
                name="reference_quantity"
                inputmode="decimal"
                required
                class="w-28"
            />
            <Select
                id="reference_unit"
                v-model="unit"
                name="reference_unit"
                :options="units"
                class="flex-1"
            />
        </div>
        <p class="text-muted-foreground text-sm">
            {{ t('foods.reference_help') }}
        </p>
        <InputError :message="errors.reference_quantity" />
        <InputError :message="errors.reference_unit" />
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="grid gap-2">
            <Label for="protein_grams" required>{{ t('foods.protein') }}</Label>
            <Input
                id="protein_grams"
                v-model="protein"
                name="protein_grams"
                inputmode="decimal"
                required
                placeholder="0"
            />
            <InputError :message="errors.protein_grams" />
        </div>

        <div class="grid gap-2">
            <Label for="fibre_grams" required>{{ t('foods.fibre') }}</Label>
            <Input
                id="fibre_grams"
                v-model="fibre"
                name="fibre_grams"
                inputmode="decimal"
                required
                placeholder="0"
            />
            <InputError :message="errors.fibre_grams" />
        </div>

        <div class="grid gap-2">
            <Label for="fat_grams">{{ t('foods.fat') }}</Label>
            <Input
                id="fat_grams"
                v-model="fat"
                name="fat_grams"
                inputmode="decimal"
                placeholder="0"
            />
            <InputError :message="errors.fat_grams" />
        </div>

        <div class="grid gap-2">
            <Label for="carbohydrate_grams">{{
                t('foods.carbohydrates')
            }}</Label>
            <Input
                id="carbohydrate_grams"
                v-model="carbohydrate"
                name="carbohydrate_grams"
                inputmode="decimal"
                placeholder="0"
            />
            <InputError :message="errors.carbohydrate_grams" />
        </div>
    </div>

    <!-- Last, because it is computed from everything above. Still one of the
         three values that drive a gauge — the display order elsewhere stays
         protein, calories, fibre. -->
    <div class="grid gap-2 sm:max-w-xs">
        <Label for="calories" required>{{ t('foods.calories') }}</Label>
        <Input
            id="calories"
            v-model="calories"
            name="calories"
            inputmode="numeric"
            required
            placeholder="0"
            :class="caloriesAreOwned ? undefined : 'text-muted-foreground'"
            @input="onCaloriesInput"
        />
        <p
            v-if="!caloriesAreOwned && suggestedCalories > 0"
            class="text-muted-foreground text-sm"
        >
            {{ t('foods.calories_suggested') }}
        </p>
        <p
            v-else-if="caloriesLookWrong"
            class="text-sm text-amber-600 dark:text-amber-500"
        >
            {{
                t('foods.calories_suspicious', {
                    expected: suggestedCalories,
                })
            }}
        </p>
        <InputError :message="errors.calories" />
    </div>
</template>
