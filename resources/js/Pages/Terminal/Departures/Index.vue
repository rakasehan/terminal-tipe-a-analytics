<template>
  <div class="max-w-6xl mx-auto p-6 bg-white rounded shadow">
    <div class="flex justify-between items-center mb-4">
      <button
        type="button"
        class="btn btn-secondary px-4 py-2 rounded shadow text-white bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 transition-all duration-200 font-semibold"
        style="background-color: #2563eb"
        @click="goToDashboard"
      >
        <span class="inline-flex items-center">
          <svg
            class="w-5 h-5 mr-2"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M15 19l-7-7 7-7"
            ></path>
          </svg>
          Kembali ke Dashboard
        </span>
      </button>
      <a
        href="/terminal/departures/create"
        class="btn btn-primary px-4 py-2 rounded shadow text-white bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 transition-all duration-200 font-semibold"
        style="background-color: #2563eb"
      >
        <span class="inline-flex items-center">
          <svg
            class="w-5 h-5 mr-2"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M12 4v16m8-8H4"
            ></path>
          </svg>
          Tambah Keberangkatan
        </span>
      </a>
    </div>
    <form
      @submit.prevent="searchDepartures"
      class="mb-4 flex flex-wrap gap-2 items-end"
    >
      <input
        v-model="filters.search"
        type="text"
        placeholder="Cari operator/rute/kendaraan..."
        class="input w-48 rounded-lg shadow focus:ring-2 focus:ring-blue-400 px-4 py-2 border border-gray-300 transition-all duration-200 bg-gray-50"
        @keyup.enter="searchDepartures"
        @input="debouncedSearch"
      />
      <input v-model="filters.start_date" type="date" class="input w-36" />
      <input v-model="filters.end_date" type="date" class="input w-36" />
      <select v-model="filters.status" class="input w-36">
        <option value="">Semua Status</option>
        <option value="scheduled">Scheduled</option>
        <option value="departed">Departed</option>
        <option value="cancelled">Cancelled</option>
        <option value="delayed">Delayed</option>
      </select>
      <select v-model="filters.per_page" class="input w-32">
        <option value="10">10 / halaman</option>
        <option value="25">25 / halaman</option>
        <option value="50">50 / halaman</option>
        <option value="100">100 / halaman</option>
      </select>
      <button type="submit" class="btn btn-primary">Cari</button>
    </form>
    <table class="min-w-full table-auto border border-gray-200 rounded">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-3 py-2 border">Operator</th>
          <th class="px-3 py-2 border">Rute</th>
          <th class="px-3 py-2 border">Kendaraan</th>
          <th class="px-3 py-2 border">Tanggal</th>
          <th class="px-3 py-2 border">Jadwal</th>
          <th class="px-3 py-2 border">Aktual</th>
          <th class="px-3 py-2 border">Penumpang</th>
          <th class="px-3 py-2 border">Okupansi (%)</th>
          <th class="px-3 py-2 border">Status</th>
          <th class="px-3 py-2 border">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="departure in departures.data" :key="departure.id">
          <td class="px-3 py-2 border">{{ departure.operator?.name }}</td>
          <td class="px-3 py-2 border">
            {{ departure.route?.code }} - {{ departure.route?.origin_city }} →
            {{ departure.route?.destination_city }}
          </td>
          <td class="px-3 py-2 border">
            {{ departure.vehicle?.plate_number }}
          </td>
          <td class="px-3 py-2 border">
            {{ formatDate(departure.departure_date) }}
          </td>
          <td class="px-3 py-2 border">{{ departure.scheduled_time }}</td>
          <td class="px-3 py-2 border">{{ departure.actual_time || "-" }}</td>
          <td class="px-3 py-2 border">{{ departure.passengers }}</td>
          <td class="px-3 py-2 border">
            {{ departure.occupancy_rate ?? "-" }}
          </td>
          <td class="px-3 py-2 border">{{ departure.status }}</td>
          <td class="px-3 py-2 border">
            <div class="flex gap-2 justify-center items-center">
              <a
                :href="`/terminal/departures/${departure.id}/edit`"
                class="inline-flex items-center px-2 py-1 rounded bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors duration-150 shadow"
                title="Edit"
              >
                <svg
                  class="w-5 h-5 mr-1"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15.232 5.232l3.536 3.536M9 13l6.536-6.536a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"
                  ></path>
                </svg>
                Edit
              </a>
              <button
                @click="deleteDeparture(departure.id)"
                class="inline-flex items-center px-2 py-1 rounded bg-red-100 text-red-700 hover:bg-red-200 transition-colors duration-150 shadow"
                title="Hapus"
              >
                <svg
                  class="w-5 h-5 mr-1"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6 18L18 6M6 6l12 12"
                  ></path>
                </svg>
                Hapus
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <div
      v-if="departures.links && departures.links.length > 1"
      class="mt-4 flex gap-2 justify-center"
    >
      <nav
        class="inline-flex -space-x-px rounded-md shadow-sm"
        aria-label="Pagination"
      >
        <button
          v-for="link in departures.links"
          :key="link.label"
          :disabled="!link.url || link.active"
          @click="goToPage(link.url)"
          v-html="link.label"
          class="px-3 py-1 border text-sm font-medium transition-colors duration-150"
          :class="[
            link.active
              ? 'bg-blue-600 text-white border-blue-600'
              : 'bg-white text-blue-600 border-gray-300 hover:bg-blue-100',
            'rounded',
            link.label.includes('Previous') ? 'rounded-l-md' : '',
            link.label.includes('Next') ? 'rounded-r-md' : '',
          ]"
        ></button>
      </nav>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from "vue";
let debounceTimeout = null;

function debouncedSearch() {
  clearTimeout(debounceTimeout);
  debounceTimeout = setTimeout(() => {
    searchDepartures();
  }, 500);
}
function goToDashboard() {
  router.visit("/terminal/dashboard");
}
function formatDate(dateStr) {
  if (!dateStr) return "";
  const d = new Date(dateStr);
  return d.toLocaleDateString("id-ID", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
}
import { router } from "@inertiajs/vue3";

const props = defineProps({
  departures: Object,
  filters: Object,
});

const filters = ref({
  search: props.filters?.search || "",
  start_date: props.filters?.start_date || "",
  end_date: props.filters?.end_date || "",
  status: props.filters?.status || "",
  per_page: props.filters?.per_page || 10,
});

function searchDepartures() {
  router.get("/terminal/departures", filters.value, { preserveState: true });
}

function goToPage(url) {
  if (url) router.visit(url, { preserveState: true });
}

function deleteDeparture(id) {
  if (confirm("Yakin ingin menghapus data keberangkatan ini?")) {
    router.delete(`/terminal/departures/${id}`);
  }
}
</script>
