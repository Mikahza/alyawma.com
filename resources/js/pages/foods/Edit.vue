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
import { index } from '@/routes/foods';
import type { Food } from '@/types';

const props = defineProps<{
    food: Food;
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Foods', href: index() }],
    },
});
</script>

<template>
    <Head :title="props.food.name" />

    <div class="max-w-2xl space-y-6 px-4 py-6">
        <Heading
            variant="small"
            title="Edit this food"
            description="Changing these values never rewrites what you have already logged"
        />

        <Form
            v-bind="FoodController.update.form(props.food.id)"
            class="space-y-6"
            v-slot="{ errors, processing }"
        >
            <FoodFields :food="props.food" :errors="errors" />

            <Button :disabled="processing" class="w-full sm:w-auto">
                Save
            </Button>
        </Form>

        <!-- Five values typed by hand should not disappear on a single tap. -->
        <div class="border-t pt-6">
            <Dialog>
                <DialogTrigger as-child>
                    <Button variant="destructive" class="w-full sm:w-auto">
                        Delete this food
                    </Button>
                </DialogTrigger>

                <DialogContent>
                    <DialogHeader>
                        <DialogTitle
                            >Delete “{{ props.food.name }}”?</DialogTitle
                        >
                        <DialogDescription>
                            It disappears from your list and from search. What
                            you have already logged with it is untouched.
                        </DialogDescription>
                    </DialogHeader>

                    <DialogFooter class="gap-2">
                        <DialogClose as-child>
                            <Button variant="secondary">Keep it</Button>
                        </DialogClose>

                        <Form
                            v-bind="FoodController.destroy.form(props.food.id)"
                            v-slot="{ processing }"
                        >
                            <Button
                                variant="destructive"
                                :disabled="processing"
                            >
                                Delete
                            </Button>
                        </Form>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </div>
</template>
