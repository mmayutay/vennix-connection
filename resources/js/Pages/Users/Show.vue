<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const deleteUser = () => {
    if (!confirm('Delete this user?')) return;

    router.delete(route('users.destroy', props.user.id), {
        onSuccess: () => router.visit(route('users.index')),
    });
};
</script>

<template>
    <Head :title="`User - ${props.user.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                User Details
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <dl class="divide-y divide-gray-200">
                            <div class="grid grid-cols-3 gap-4 py-3">
                                <dt class="text-sm font-medium text-gray-500">
                                    Name
                                </dt>
                                <dd class="col-span-2 text-sm text-gray-900">
                                    {{ props.user.name }}
                                </dd>
                            </div>

                            <div class="grid grid-cols-3 gap-4 py-3">
                                <dt class="text-sm font-medium text-gray-500">
                                    Email
                                </dt>
                                <dd class="col-span-2 text-sm text-gray-900">
                                    {{ props.user.email }}
                                </dd>
                            </div>

                            <div class="grid grid-cols-3 gap-4 py-3">
                                <dt class="text-sm font-medium text-gray-500">
                                    Verified
                                </dt>
                                <dd class="col-span-2 text-sm text-gray-900">
                                    <span
                                        v-if="props.user.email_verified_at"
                                        class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800"
                                    >Verified</span>
                                    <span
                                        v-else
                                        class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800"
                                    >Unverified</span>
                                </dd>
                            </div>

                            <div class="grid grid-cols-3 gap-4 py-3">
                                <dt class="text-sm font-medium text-gray-500">
                                    Created at
                                </dt>
                                <dd class="col-span-2 text-sm text-gray-900">
                                    {{ new Date(props.user.created_at).toLocaleString() }}
                                </dd>
                            </div>
                        </dl>

                        <div class="mt-6 flex items-center justify-between">
                            <Link
                                :href="route('users.index')"
                                class="text-sm text-gray-600 underline hover:text-gray-900"
                            >
                                Back to list
                            </Link>

                            <div class="space-x-3">
                                <SecondaryButton
                                    as="a"
                                    :href="route('users.edit', props.user.id)"
                                >
                                    Edit
                                </SecondaryButton>

                                <PrimaryButton
                                    type="button"
                                    class="bg-red-600 hover:bg-red-700 focus:ring-red-500"
                                    @click="deleteUser"
                                >
                                    Delete
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

