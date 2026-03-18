<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, defineProps } from 'vue';
import inventoryService from '@/services/inventoryService';

const props = defineProps({
    inventories: Object,
});
const inventories = ref([]);
const pagination = ref(null);
const loading = ref(false);
const search = ref('');
const error = ref(null);

const deleteInventory = async (id) => {
    if (!confirm('Delete this inventory item?')) return;

    loading.value = true;
    error.value = null;

    try {
        await inventoryService.remove(id);
        await fetchInventories();
    } catch (e) {
        error.value = 'Failed to delete inventory item.';
        // eslint-disable-next-line no-console
        console.error(e);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    console.log(props.inventories.data);
});

</script>

<template>
    <Head title="Inventory" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Inventory
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <h3 class="text-lg font-semibold">
                                Inventory Items
                            </h3>

                            <div class="flex flex-1 flex-col gap-2 sm:flex-row sm:items-center sm:justify-end">
                                <div class="flex-1 sm:max-w-xs">
                                    <input
                                        v-model="search"
                                        type="text"
                                        placeholder="Search by name or SKU"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        @keyup.enter="fetchInventories()"
                                    >
                                </div>

                                <button
                                    type="button"
                                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    @click="fetchInventories()"
                                >
                                    Search
                                </button>

                                <Link
                                    :href="route('inventory.create')"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    Add Item
                                </Link>
                            </div>
                        </div>

                        <p
                            v-if="error"
                            class="mb-4 text-sm text-red-600"
                        >
                            {{ error }}
                        </p>

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
                                            SKU
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Quantity
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Price
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Location
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Category
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
                                        v-for="item in props.inventories.data"
                                        :key="item.id"
                                    >
                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900">
                                            {{ item.name }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900">
                                            {{ item.sku }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-right text-gray-900">
                                            {{ item.quantity }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-right text-gray-900">
                                            {{ Number(item.price).toFixed(2) }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900">
                                            {{ item.location || '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900">
                                            {{ item.category || '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-right text-sm font-medium space-x-2">
                                            <Link
                                                :href="route('inventory.edit', item.id)"
                                                class="text-yellow-600 hover:text-yellow-900"
                                            >
                                                Edit
                                            </Link>
                                            <button
                                                type="button"
                                                class="text-red-600 hover:text-red-900"
                                                @click="deleteInventory(item.id)"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="!loading && inventories.length === 0">
                                        <td
                                            colspan="7"
                                            class="px-4 py-4 text-center text-sm text-gray-500"
                                        >
                                            No inventory items found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div
                            v-if="pagination"
                            class="mt-4 flex items-center justify-between"
                        >
                            <div class="text-sm text-gray-600">
                                Showing
                                <span class="font-medium">
                                    {{ pagination.from || 0 }}
                                </span>
                                to
                                <span class="font-medium">
                                    {{ pagination.to || 0 }}
                                </span>
                                of
                                <span class="font-medium">
                                    {{ pagination.total || 0 }}
                                </span>
                                items
                            </div>

                            <div class="space-x-2">
                                <button
                                    v-if="pagination.prev_page_url"
                                    type="button"
                                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
                                    @click="fetchInventories(pagination.prev_page_url)"
                                >
                                    Previous
                                </button>
                                <button
                                    v-if="pagination.next_page_url"
                                    type="button"
                                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
                                    @click="fetchInventories(pagination.next_page_url)"
                                >
                                    Next
                                </button>
                            </div>
                        </div>

                        <div
                            v-if="loading"
                            class="mt-4 text-sm text-gray-500"
                        >
                            Loading...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

