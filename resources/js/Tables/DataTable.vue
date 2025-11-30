<script setup lang="ts" generic="T">
import { Link } from '@inertiajs/vue3';
import type { PaginatedData } from '@/types';

interface Column {
    key: string;
    label: string;
    sortable?: boolean;
}

interface Props {
    data: PaginatedData<T>;
    columns: Column[];
}

defineProps<Props>();
</script>

<template>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th
                        v-for="column in columns"
                        :key="column.key"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                        {{ column.label }}
                    </th>
                    <th v-if="$slots.actions" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(item, index) in data.data" :key="index" class="hover:bg-gray-50">
                    <td
                        v-for="column in columns"
                        :key="column.key"
                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                    >
                        <slot :name="`cell-${column.key}`" :item="item">
                            {{ (item as any)[column.key] }}
                        </slot>
                    </td>
                    <td v-if="$slots.actions" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <slot name="actions" :item="item" />
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="data.last_page > 1" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <Link
                    v-if="data.current_page > 1"
                    :href="route(route().current()!, { ...route().params, page: data.current_page - 1 })"
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                    Previous
                </Link>
                <Link
                    v-if="data.current_page < data.last_page"
                    :href="route(route().current()!, { ...route().params, page: data.current_page + 1 })"
                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                    Next
                </Link>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ data.from }}</span>
                        to
                        <span class="font-medium">{{ data.to }}</span>
                        of
                        <span class="font-medium">{{ data.total }}</span>
                        results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                        <Link
                            v-for="page in data.last_page"
                            :key="page"
                            :href="route(route().current()!, { ...route().params, page })"
                            :class="[
                                'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                page === data.current_page
                                    ? 'z-10 bg-primary-50 border-primary-500 text-primary-600'
                                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                            ]"
                        >
                            {{ page }}
                        </Link>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>