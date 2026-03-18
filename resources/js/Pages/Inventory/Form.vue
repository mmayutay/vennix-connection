<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import inventoryService from '@/services/inventoryService';

const props = defineProps({
    inventory: {
        type: Object,
        default: null,
    },
});

const isEdit = computed(() => !!props.inventory);

const form = useForm({
    name: props.inventory?.name ?? '',
    sku: props.inventory?.sku ?? '',
    description: props.inventory?.description ?? '',
    quantity: props.inventory?.quantity ?? 0,
    price: props.inventory?.price ?? 0,
    location: props.inventory?.location ?? '',
    category: props.inventory?.category ?? '',
});

const submitting = computed(() => form.processing);

const submit = async () => {
    form.clearErrors();

    try {

        form.post(route('inventory.store'), {
            onSuccess: () => {
                form.reset();
                window.location = route('inventory.index');
            },
        });

        // if (isEdit.value) {
        //     await inventoryService.update(props.inventory.id, form.data());
        // } else {
        //     await inventoryService.create(form.data());
        // }

        // form.reset();
        // window.location = route('inventory.index');
    } catch (error) {
        if (error.response?.status === 422 && error.response.data?.errors) {
            form.setError(error.response.data.errors);
        } else {
            // eslint-disable-next-line no-alert
            alert('Something went wrong. Please try again.');
            // eslint-disable-next-line no-console
            console.error(error);
        }
    }
};
</script>

<template>
    <Head :title="isEdit ? 'Edit Inventory' : 'Create Inventory'" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ isEdit ? 'Edit Inventory Item' : 'Create Inventory Item' }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form
                            class="space-y-6"
                            @submit.prevent="submit"
                        >
                            <div>
                                <InputLabel
                                    for="name"
                                    value="Name"
                                />

                                <TextInput
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.name"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="sku"
                                    value="SKU (Stock Keeping Unit)"
                                />

                                <TextInput
                                    id="sku"
                                    v-model="form.sku"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.sku"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="description"
                                    value="Description"
                                />

                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.description"
                                />
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <InputLabel
                                        for="quantity"
                                        value="Quantity"
                                    />

                                    <TextInput
                                        id="quantity"
                                        v-model.number="form.quantity"
                                        type="number"
                                        min="0"
                                        class="mt-1 block w-full"
                                        required
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.quantity"
                                    />
                                </div>

                                <div>
                                    <InputLabel
                                        for="price"
                                        value="Price"
                                    />

                                    <TextInput
                                        id="price"
                                        v-model.number="form.price"
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        class="mt-1 block w-full"
                                        required
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.price"
                                    />
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <InputLabel
                                        for="location"
                                        value="Location"
                                    />

                                    <TextInput
                                        id="location"
                                        v-model="form.location"
                                        type="text"
                                        class="mt-1 block w-full"
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.location"
                                    />
                                </div>

                                <div>
                                    <InputLabel
                                        for="category"
                                        value="Category"
                                    />

                                    <TextInput
                                        id="category"
                                        v-model="form.category"
                                        type="text"
                                        class="mt-1 block w-full"
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.category"
                                    />
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <Link
                                    :href="route('inventory.index')"
                                    class="text-sm font-medium text-gray-600 hover:text-gray-900"
                                >
                                    Cancel
                                </Link>

                                <PrimaryButton :disabled="submitting">
                                    <span v-if="submitting">
                                        Saving...
                                    </span>
                                    <span v-else>
                                        {{ isEdit ? 'Update Item' : 'Create Item' }}
                                    </span>
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

