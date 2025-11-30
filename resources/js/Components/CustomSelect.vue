<template>
  <div class="relative">
    <button
      type="button"
      class="w-full select px-4 py-2 text-left bg-white border border-gray-300 rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-150"
      @click="toggleDropdown"
    >
      <span v-if="selectedLabel" class="text-gray-900 font-medium">{{
        selectedLabel
      }}</span>
      <span v-else class="text-gray-400">{{ placeholder }}</span>
      <svg
        class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M19 9l-7 7-7-7"
        />
      </svg>
    </button>
    <transition name="fade">
      <div
        v-if="open"
        class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden"
      >
        <ul>
          <li
            v-for="option in options"
            :key="option.value"
            @click="selectOption(option)"
            class="px-4 py-2 cursor-pointer hover:bg-blue-100 text-gray-900 transition-colors duration-100"
          >
            {{ option.label }}
          </li>
        </ul>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, watch, computed } from "vue";

const props = defineProps({
  modelValue: [String, Number],
  options: Array,
  placeholder: String,
});
const emit = defineEmits(["update:modelValue"]);

const open = ref(false);
const selectedLabel = computed(() => {
  const found = props.options.find((opt) => opt.value === props.modelValue);
  return found ? found.label : "";
});

function toggleDropdown() {
  open.value = !open.value;
}
function selectOption(option) {
  emit("update:modelValue", option.value);
  open.value = false;
}
watch(
  () => props.modelValue,
  () => {
    open.value = false;
  }
);
</script>

<style scoped>
.select {
  font-size: 1rem;
  font-weight: 500;
  color: #111827;
  background: #fff;
  border-radius: 0.5rem;
  transition: box-shadow 0.15s;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
