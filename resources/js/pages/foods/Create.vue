<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import FoodController from '@/actions/App/Http/Controllers/FoodController';
import FoodFields from '@/components/FoodFields.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { create, index } from '@/routes/foods';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Foods', href: index() },
            { title: 'Add a food', href: create() },
        ],
    },
});
</script>

<template>
    <Head title="Add a food" />

    <div class="max-w-2xl space-y-6 px-4 py-6">
        <Heading
            variant="small"
            title="Add a food"
            description="An ingredient or a whole meal — the values are yours to enter"
        />

        <Form
            v-bind="FoodController.store.form()"
            class="space-y-6"
            v-slot="{ errors, processing }"
        >
            <FoodFields :errors="errors" />

            <Button :disabled="processing" class="w-full sm:w-auto">
                Add this food
            </Button>
        </Form>
    </div>
</template>
