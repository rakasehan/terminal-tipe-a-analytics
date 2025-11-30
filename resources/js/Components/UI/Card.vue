<script setup lang="ts">
defineProps<{
    title?: string;
    padding?: boolean;
}>();
</script>

<template>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div v-if="title || $slots.header" class="px-6 py-4 border-b border-gray-200">
            <slot name="header">
                <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
            </slot>
        </div>
        <div :class="padding !== false ? 'p-6' : ''">
            <slot />
        </div>
        <div v-if="$slots.footer" class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <slot name="footer" />
        </div>
    </div>
</template>