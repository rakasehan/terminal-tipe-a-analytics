<script setup lang="ts">
import { computed } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Card from "@/Components/UI/Card.vue";
import LineChart from "@/Components/Charts/LineChart.vue";
import BarChart from "@/Components/Charts/BarChart.vue";
import type { Statistics, Terminal, PaginatedData, Departure } from "@/types";

interface Props {
  statistics: Statistics & { total_terminals: number };
  terminals: Array<{ terminal: Terminal; statistics: Statistics }>;
  recentDepartures: PaginatedData<Departure>;
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

// Sample chart data
const passengerTrendData = computed(() => ({
  labels: props.terminals.map((t) => t.terminal.name),
  datasets: [
    {
      label: "Total Passengers",
      data: props.terminals.map((t) => t.statistics.total_passengers),
      backgroundColor: "rgba(59, 130, 246, 0.5)",
      borderColor: "rgba(59, 130, 246, 1)",
      borderWidth: 2,
    },
  ],
}));
</script>

<template>
  <AdminLayout title="Dashboard">
    <div class="space-y-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard BPTD</h1>
        <p class="mt-1 text-sm text-gray-600">
          Overview seluruh terminal dari {{ filters?.start_date ?? "-" }} sampai
          {{ filters?.end_date ?? "-" }}
        </p>
      </div>

      <div v-if="statistics">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <Card>
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div
                  class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg"
                >
                  <span class="text-2xl">🏢</span>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Terminal</p>
                <p class="text-2xl font-bold text-gray-900">
                  {{ statistics.total_terminals ?? "-" }}
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
                  <span class="text-2xl">🚌</span>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">
                  Total Keberangkatan
                </p>
                <p class="text-2xl font-bold text-gray-900">
                  {{ formatNumber(statistics.total_departures ?? 0) }}
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
                  {{ formatNumber(statistics.total_passengers ?? 0) }}
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
                  <span class="text-2xl">💰</span>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">
                  Total Pendapatan
                </p>
                <p class="text-xl font-bold text-gray-900">
                  {{ formatCurrency(statistics.total_revenue ?? 0) }}
                </p>
              </div>
            </div>
          </Card>
        </div>
      </div>
      <div v-else class="text-center py-10 text-gray-400">
        Data statistik belum tersedia.
      </div>

      <div v-if="terminals && terminals.length">
        <!-- Charts -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <Card title="Penumpang per Terminal">
            <BarChart :data="passengerTrendData" :height="300" />
            <div
              v-if="!statistics && !terminals && !recentDepartures"
              class="text-center py-20 text-gray-400"
            >
              Dashboard minimal tampil. Tidak ada data atau komponen lain yang
              berhasil di-load.<br />
              Silakan cek koneksi backend dan error di console browser.
            </div>
          </Card>
          <Card title="Okupansi Terminal">
            <div class="space-y-4">
              <div
                v-for="item in terminals"
                :key="item.terminal.id"
                class="flex items-center justify-between"
              >
                <span class="text-sm font-medium text-gray-700">{{
                  item.terminal.name
                }}</span>
                <div class="flex items-center space-x-2">
                  <div class="w-48 bg-gray-200 rounded-full h-2">
                    <div
                      class="bg-primary-600 h-2 rounded-full"
                      :style="{
                        width: `${item.statistics.average_occupancy ?? 0}%`,
                      }"
                    ></div>
                  </div>
                  <span class="text-sm font-semibold text-gray-900">
                    {{
                      typeof item.statistics.average_occupancy === "number"
                        ? item.statistics.average_occupancy.toFixed(1)
                        : Number(
                            item.statistics.average_occupancy ?? 0
                          ).toFixed(1)
                    }}%
                  </span>
                </div>
              </div>
            </div>
          </Card>
        </div>
      </div>
      <div v-else class="text-center py-10 text-gray-400">
        Data terminal belum tersedia.
      </div>

      <div
        v-if="
          recentDepartures &&
          recentDepartures.data &&
          recentDepartures.data.length
        "
      >
        <!-- Recent Departures -->
        <Card title="Keberangkatan Terbaru">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                  >
                    Terminal
                  </th>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                  >
                    Rute
                  </th>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                  >
                    Tanggal
                  </th>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                  >
                    Penumpang
                  </th>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                  >
                    Status
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr
                  v-for="departure in recentDepartures.data"
                  :key="departure.id"
                >
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ departure.terminal?.name ?? "-" }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ departure.route?.full_name ?? "-" }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ departure.departure_date ?? "-" }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ departure.passengers ?? "-" }} /
                    {{ departure.seat_capacity ?? "-" }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      :class="[
                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                        departure.status === 'departed'
                          ? 'bg-green-100 text-green-800'
                          : departure.status === 'scheduled'
                          ? 'bg-blue-100 text-blue-800'
                          : departure.status === 'cancelled'
                          ? 'bg-red-100 text-red-800'
                          : 'bg-yellow-100 text-yellow-800',
                      ]"
                    >
                      {{ departure.status ?? "-" }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </Card>
      </div>
      <div v-else class="text-center py-10 text-gray-400">
        Data keberangkatan belum tersedia.
      </div>
    </div>
  </AdminLayout>
</template>
