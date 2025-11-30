<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

defineProps<{
    title?: string;
}>();
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <Head :title="title" />

        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-primary-600">Terminal Analytics</h1>
                <p class="mt-2 text-sm text-gray-600">Sistem Analisis Terminal Tipe A - BPTD</p>
            </div>

            <slot />
        </div>

        <div class="mt-6 text-center text-sm text-gray-600">
            <p>&copy; {{ new Date().getFullYear() }} BPTD. All rights reserved.</p>
        </div>
    </div>
</template>