<script setup lang="ts">
import { ref } from 'vue';
import {
    Chart as ChartJS,
    ArcElement,
    Tooltip,
    Legend,
    type ChartData,
    type ChartOptions
} from 'chart.js';
import { Pie } from 'vue-chartjs';

ChartJS.register(ArcElement, Tooltip, Legend);

interface Props {
    data: ChartData<'pie'>;
    options?: ChartOptions<'pie'>;
    height?: number;
}

const props = withDefaults(defineProps<Props>(), {
    height: 300,
});

const defaultOptions: ChartOptions<'pie'> = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: true,
            position: 'right',
        },
    },
};

const chartOptions = ref({ ...defaultOptions, ...props.options });
</script>

<template>
    <div :style="{ height: `${height}px` }">
        <Pie :data="data" :options="chartOptions" />
    </div>
</template>