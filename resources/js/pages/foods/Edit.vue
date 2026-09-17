<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import FoodController from '@/actions/App/Http/Controllers/FoodController';
import FoodFields from '@/components/FoodFields.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { t } from '@/plugins/i18n';
import { index } from '@/routes/foods';
import type { Food } from '@/types';

const props = defineProps<{
    food: Food;
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ titleKey: 'nav.foods', href: index() }],
    },
});
</script>

<template>
    <Head :title="props.food.name" />

    <div class="max-w-2xl space-y-6 px-4 py-6">
        <Heading
            variant="small"
            :title="t('foods.edit_title')"
            :description="t('foods.edit_subtitle')"
        />

        <Form
            v-bind="FoodController.update.form(props.food.id)"
            class="space-y-6"
            v-slot="{ errors, processing }"
        >
            <FoodFields :food="props.food" :errors="errors" />

            <Button :disabled="processing" class="w-full sm:w-auto">
                {{ t('common.save') }}
            </Button>
        </Form>

        <!-- Five values typed by hand should not disappear on a single tap. -->
        <div class="border-t pt-6">
            <Dialog>
                <DialogTrigger as-child>
                    <Button variant="destructive" class="w-full sm:w-auto">
                        {{ t('foods.delete') }}
                    </Button>
                </DialogTrigger>

                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>{{
                            t('foods.delete_confirm_title', {
                                name: props.food.name,
                            })
                        }}</DialogTitle>
                        <DialogDescription>
                            {{ t('foods.delete_confirm_body') }}
                        </DialogDescription>
                    </DialogHeader>

                    <DialogFooter class="gap-2">
                        <DialogClose as-child>
                            <Button variant="secondary">{{
                                t('foods.delete_keep')
                            }}</Button>
                        </DialogClose>

                        <Form
                            v-bind="FoodController.destroy.form(props.food.id)"
                            v-slot="{ processing }"
                        >
                            <Button
                                variant="destructive"
                                :disabled="processing"
                            >
                                {{ t('common.delete') }}
                            </Button>
                        </Form>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </div>
</template>
