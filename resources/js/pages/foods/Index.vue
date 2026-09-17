<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { watchDebounced } from '@vueuse/core';
import { Plus } from '@lucide/vue';
import { ref } from 'vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { create, edit, index } from '@/routes/foods';
import type { Food } from '@/types';

const props = defineProps<{
    foods: Food[];
    search: string;
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Foods', href: index() }],
    },
});

const search = ref(props.search);

// Results follow the typing rather than a submit. Only the list is refetched,
// the field keeps its focus and the history is replaced instead of stacked.
watchDebounced(
    search,
    (terms) => {
        router.get(
            index().url,
            { search: terms },
            {
                only: ['foods', 'search'],
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    },
    { debounce: 250 },
);
</script>

<template>
    <Head title="Foods" />

    <div class="max-w-3xl px-4 py-6 max-sm:pb-24">
        <div class="flex items-start justify-between gap-4">
            <Heading
                variant="small"
                title="Your foods"
                description="Every food you log comes from this list"
            />

            <!-- On a wide screen the action belongs beside the title. On a phone
                 it moves to the bottom bar below, within reach of a thumb. -->
            <Button as-child class="max-sm:hidden">
                <Link :href="create()">
                    <Plus class="size-4" />
                    Add a food
                </Link>
            </Button>
        </div>

        <Input
            v-model="search"
            type="search"
            class="mt-4 block w-full"
            autocomplete="off"
            placeholder="Search your foods…"
            aria-label="Search your foods"
        />

        <p
            v-if="foods.length === 0"
            class="text-muted-foreground py-10 text-sm"
        >
            {{
                search
                    ? `Nothing matches “${search}”.`
                    : 'Nothing yet. Add the foods you eat often and logging them will take three taps.'
            }}
        </p>

        <ul v-else class="divide-border mt-2 divide-y">
            <li v-for="food in foods" :key="food.id">
                <Link
                    :href="edit(food.id)"
                    class="hover:bg-muted/50 -mx-2 flex items-baseline justify-between gap-4 rounded-md px-2 py-4 transition-colors"
                >
                    <span class="min-w-0">
                        <span class="block truncate font-medium">{{
                            food.name
                        }}</span>
                        <span class="text-muted-foreground block text-sm"
                            >per {{ food.reference_quantity }}
                            {{ food.reference_unit }}</span
                        >
                    </span>
                    <span class="shrink-0 text-right text-sm">
                        <span class="block font-medium"
                            >{{ food.protein_grams }} g</span
                        >
                        <span class="text-muted-foreground block"
                            >{{ food.calories }} kcal</span
                        >
                    </span>
                </Link>
            </li>
        </ul>
    </div>

    <!-- Phones only: the one action reached one-handed, standing in a kitchen. -->
    <div
        class="bg-background/80 fixed inset-x-0 bottom-0 border-t p-4 backdrop-blur sm:hidden"
    >
        <Button as-child class="w-full">
            <Link :href="create()">Add a food</Link>
        </Button>
    </div>
</template>
