<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import FoodController from '@/actions/App/Http/Controllers/FoodController';
import FoodFields from '@/components/FoodFields.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { t } from '@/plugins/i18n';
import { create, index } from '@/routes/foods';

defineOptions({
    layout: {
        breadcrumbs: [
            { titleKey: 'nav.foods', href: index() },
            { titleKey: 'foods.create_title', href: create() },
        ],
    },
});
</script>

<template>
    <Head :title="t('foods.create_title')" />

    <div class="max-w-2xl space-y-6 px-4 py-6">
        <Heading
            variant="small"
            :title="t('foods.create_title')"
            :description="t('foods.create_subtitle')"
        />

        <Form
            v-bind="FoodController.store.form()"
            class="space-y-6"
            v-slot="{ errors, processing }"
        >
            <FoodFields :errors="errors" />

            <Button :disabled="processing" class="w-full sm:w-auto">
                {{ t('foods.create_submit') }}
            </Button>
        </Form>
    </div>
</template>
