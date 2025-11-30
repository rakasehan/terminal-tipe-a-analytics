<script setup lang="ts">
import { onMounted, onUnmounted } from 'vue';

interface Props {
    show: boolean;
    maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl';
}

const props = withDefaults(defineProps<Props>(), {
    show: false,
    maxWidth: 'md',
});

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const close = () => {
    emit('close');
};

const closeOnEscape = (e: KeyboardEvent) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const maxWidthClass = {
    sm: 'sm:max-w-sm',
    md: 'sm:max-w-md',
    lg: 'sm:max-w-lg',
    xl: 'sm:max-w-xl',
    '2xl': 'sm:max-w-2xl',
}[props.maxWidth];
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-show="show"
                class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0"
                @click="close"
            >
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <div class="relative min-h-screen flex items-center justify-center">
                    <Transition
                        enter-active-class="transition ease-out duration-200"
                        enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                        leave-active-class="transition ease-in duration-150"
                        leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                        leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <div
                            v-show="show"
                            :class="['relative bg-white rounded-lg shadow-xl w-full', maxWidthClass]"
                            @click.stop
                        >
                            <slot />
                        </div>
                    </Transition>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>