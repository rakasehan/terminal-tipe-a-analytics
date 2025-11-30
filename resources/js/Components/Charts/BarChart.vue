<script setup lang="ts">
import { ref } from 'vue';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend,
    type ChartData,
    type ChartOptions
} from 'chart.js';
import { Bar } from 'vue-chartjs';

ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend
);

interface Props {
    data: ChartData<'bar'>;
    options?: ChartOptions<'bar'>;
    height?: number;
}

const props = withDefaults(defineProps<Props>(), {
    height: 300,
});

const defaultOptions: ChartOptions<'bar'> = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: true,
            position: 'top',
        },
    },
    scales: {
        y: {
            beginAtZero: true,
        },
    },
};

const chartOptions = ref({ ...defaultOptions, ...props.options });
</script>

<template>
    <div :style="{ height: `${height}px` }">
        <Bar :data="data" :options="chartOptions" />
    </div>
</template>