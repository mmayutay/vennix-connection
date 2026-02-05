<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
});

const deleteUser = (id) => {
    if (!confirm('Delete this user?')) return;

    router.delete(route('users.destroy', id));
};
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Users
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold">
                                All Users
                            </h3>

                            <Link
                                :href="route('users.create')"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Add User
                            </Link>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Name
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Email
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Verified
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Created
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr
                                        v-for="user in props.users.data"
                                        :key="user.id"
                                    >
                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900">
                                            {{ user.name }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900">
                                            {{ user.email }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm">
                                            <span
                                                v-if="user.email_verified_at"
                                                class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800"
                                            >Verified</span>
                                            <span
                                                v-else
                                                class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800"
                                            >Unverified</span>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500">
                                            {{ new Date(user.created_at).toLocaleString() }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-right text-sm font-medium space-x-2">
                                            <Link
                                                :href="route('users.show', user.id)"
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                View
                                            </Link>
                                            <Link
                                                :href="route('users.edit', user.id)"
                                                class="text-yellow-600 hover:text-yellow-900"
                                            >
                                                Edit
                                            </Link>
                                            <button
                                                type="button"
                                                class="text-red-600 hover:text-red-900"
                                                @click="deleteUser(user.id)"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                Showing
                                <span class="font-medium">
                                    {{ props.users.from || 0 }}
                                </span>
                                to
                                <span class="font-medium">
                                    {{ props.users.to || 0 }}
                                </span>
                                of
                                <span class="font-medium">
                                    {{ props.users.total }}
                                </span>
                                users
                            </div>

                            <div class="space-x-2">
                                <Link
                                    v-if="props.users.prev_page_url"
                                    :href="props.users.prev_page_url"
                                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
                                >
                                    Previous
                                </Link>
                                <Link
                                    v-if="props.users.next_page_url"
                                    :href="props.users.next_page_url"
                                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
                                >
                                    Next
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

