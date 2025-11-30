<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types';

defineProps<{
    title?: string;
}>();

const page = usePage<PageProps>();
const user = computed(() => page.props.auth.user);
const isSuperAdmin = computed(() => user.value?.is_super_admin);

const showingNavigationDropdown = ref(false);
const sidebarOpen = ref(true);

const logout = () => {
    router.post(route('logout'));
};

const navigation = computed(() => {
    if (isSuperAdmin.value) {
        return [
            { name: 'Dashboard', route: 'admin.dashboard', icon: '📊' },
            { name: 'Terminal', route: 'admin.terminals.index', icon: '🏢' },
            { name: 'Users', route: 'admin.users.index', icon: '👥' },
            { name: 'Reports', route: 'admin.reports.index', icon: '📄' },
        ];
    }

    return [
        { name: 'Dashboard', route: 'terminal.dashboard', icon: '📊' },
        { name: 'Departures', route: 'terminal.departures.index', icon: '🚌' },
        { name: 'Financial', route: 'terminal.financial.index', icon: '💰' },
        { name: 'Statistics', route: 'terminal.statistics.index', icon: '📈' },
        { name: 'Reports', route: 'terminal.reports.index', icon: '📄' },
    ];
});
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <Head :title="title" />

        <!-- Sidebar -->
        <aside
            :class="[
                'fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full'
            ]"
        >
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 border-b border-gray-200">
                    <h1 class="text-xl font-bold text-primary-600">Terminal Analytics</h1>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    <Link
                        v-for="item in navigation"
                        :key="item.route"
                        :href="route(item.route)"
                        :class="[
                            'flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors',
                            route().current(item.route + '*')
                                ? 'bg-primary-50 text-primary-700'
                                : 'text-gray-700 hover:bg-gray-100'
                        ]"
                    >
                        <span class="mr-3 text-lg">{{ item.icon }}</span>
                        {{ item.name }}
                    </Link>
                </nav>

                <!-- User Info -->
                <div class="p-4 border-t border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                                <span class="text-primary-600 font-semibold">
                                    {{ user?.name.charAt(0).toUpperCase() }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {{ user?.name }}
                            </p>
                            <p class="text-xs text-gray-500 truncate">
                                {{ user?.terminal?.name || 'BPTD Admin' }}
                            </p>
                        </div>
                    </div>
                    <button
                        @click="logout"
                        class="mt-3 w-full px-4 py-2 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors"
                    >
                        Logout
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div :class="['transition-all duration-300', sidebarOpen ? 'ml-64' : 'ml-0']">
            <!-- Top Bar -->
            <header class="bg-white border-b border-gray-200 sticky top-0 z-40">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <button
                            @click="sidebarOpen = !sidebarOpen"
                            class="p-2 rounded-lg text-gray-600 hover:bg-gray-100"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600">
                                {{ new Date().toLocaleDateString('id-ID', { 
                                    weekday: 'long', 
                                    year: 'numeric', 
                                    month: 'long', 
                                    day: 'numeric' 
                                }) }}
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 sm:p-6 lg:p-8">
                <!-- Flash Messages -->
                <div v-if="$page.props.flash.success" class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                    {{ $page.props.flash.success }}
                </div>
                <div v-if="$page.props.flash.error" class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                    {{ $page.props.flash.error }}
                </div>

                <slot />
            </main>
        </div>
    </div>
</template>