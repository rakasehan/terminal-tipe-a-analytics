<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    modelValue: string | number;
    label?: string;
    options: Array<{ value: string | number; label: string }>;
    error?: string;
    required?: boolean;
    disabled?: boolean;
    placeholder?: string;
}

const props = withDefaults(defineProps<Props>(), {
    required: false,
    disabled: false,
    placeholder: 'Select an option',
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string | number): void;
}>();

const selectClasses = computed(() => {
    const base = 'w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-offset-0 transition-colors';
    const normal = 'border-gray-300 focus:border-primary-500 focus:ring-primary-200';
    const errorClass = 'border-red-300 focus:border-red-500 focus:ring-red-200';
    
    return `${base} ${props.error ? errorClass : normal}`;
});
</script>

<template>
    <div class="w-full">
        <label v-if="label" class="block text-sm font-medium text-gray-700 mb-1">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>
        <select
            :value="modelValue"
            @change="emit('update:modelValue', ($event.target as HTMLSelectElement).value)"
            :disabled="disabled"
            :class="selectClasses"
        >
            <option value="" disabled>{{ placeholder }}</option>
            <option
                v-for="option in options"
                :key="option.value"
                :value="option.value"
            >
                {{ option.label }}
            </option>
        </select>
        <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    </div>
</template>