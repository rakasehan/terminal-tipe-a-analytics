<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    type ChartData,
    type ChartOptions
} from 'chart.js';
import { Line } from 'vue-chartjs';

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
);

interface Props {
    data: ChartData<'line'>;
    options?: ChartOptions<'line'>;
    height?: number;
}

const props = withDefaults(defineProps<Props>(), {
    height: 300,
});

const defaultOptions: ChartOptions<'line'> = {
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
        <Line :data="data" :options="chartOptions" />
    </div>
</template>