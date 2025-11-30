<script setup lang="ts">
import { computed } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Card from "@/Components/UI/Card.vue";
import LineChart from "@/Components/Charts/LineChart.vue";
import type { Terminal, Statistics } from "@/types";

interface Props {
  data: {
    terminal: Terminal;
    statistics: Statistics;
    financial_summary: any;
  };
  analytics: {
    daily_trend: Array<{ date: string; arrivals: number; departures: number }>;
    peak_hours: Record<number, number>;
  };
  filters: {
    start_date: string;
    end_date: string;
  };
}

const props = defineProps<Props>();

const formatCurrency = (value: number) => {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
  }).format(value);
};

const formatNumber = (value: number) => {
  return new Intl.NumberFormat("id-ID").format(value);
};

const dailyTrendChartData = computed(() => ({
  labels: props.analytics.daily_trend.map((d) => d.date),
  datasets: [
    {
      label: "Penumpang",
      data: props.analytics.daily_trend.map((d) => d.departures),
      borderColor: "rgba(59, 130, 246, 1)",
      backgroundColor: "rgba(59, 130, 246, 0.1)",
      tension: 0.4,
    },
  ],
}));
</script>

<template>
  <AppLayout title="Dashboard">
    <div class="space-y-6">
      <!-- Page Header -->
      <div>
        <h1 class="text-2xl font-bold text-gray-900">
          {{ data.terminal.name }}
        </h1>
        <p class="mt-1 text-sm text-gray-600">
          {{ data.terminal.city }}, {{ data.terminal.province }}
        </p>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <Card>
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg"
              >
                <span class="text-2xl">🚌</span>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">
                Total Keberangkatan
              </p>
              <p class="text-2xl font-bold text-gray-900">
                {{ formatNumber(data.statistics.total_departures) }}
              </p>
            </div>
          </div>
        </Card>

        <Card>
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg"
              >
                <span class="text-2xl">👥</span>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Penumpang</p>
              <p class="text-2xl font-bold text-gray-900">
                {{ formatNumber(data.statistics.total_passengers) }}
              </p>
            </div>
          </div>
        </Card>

        <Card>
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg"
              >
                <span class="text-2xl">💰</span>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Pendapatan</p>
              <p class="text-lg font-bold text-gray-900">
                {{ formatCurrency(data.statistics.total_revenue) }}
              </p>
            </div>
          </div>
        </Card>

        <Card>
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div
                class="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg"
              >
                <span class="text-2xl">📊</span>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">
                Rata-rata Okupansi
              </p>
              <p class="text-2xl font-bold text-gray-900">
                {{
                  typeof data.statistics.average_occupancy === "number"
                    ? data.statistics.average_occupancy.toFixed(1)
                    : Number(data.statistics.average_occupancy ?? 0).toFixed(1)
                }}%
              </p>
            </div>
          </div>
        </Card>
      </div>

      <!-- Charts -->
      <div class="grid grid-cols-1 gap-6">
        <Card title="Trend Penumpang Harian">
          <LineChart :data="dailyTrendChartData" :height="300" />
        </Card>
      </div>
    </div>
  </AppLayout>
</template>
