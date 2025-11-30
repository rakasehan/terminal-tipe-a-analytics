<script setup lang="ts">
import AppLayout from './AppLayout.vue';

defineProps<{
    title?: string;
}>();
</script>

<template>
    <AppLayout :title="title">
        <slot />
    </AppLayout>
</template>