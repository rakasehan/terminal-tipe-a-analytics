<template>
  <div class="max-w-2xl mx-auto mt-10">
    <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
      <h2 class="text-3xl font-extrabold mb-8 text-blue-700 text-center tracking-tight">Tambah Keberangkatan</h2>
      <form @submit.prevent="submit">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <label>
          Terminal
          <input
            :value="props.terminal_name"
            type="text"
            class="input"
            readonly
          />
        </label>
        <label>
          Operator
          <select v-model="form.operator_id" class="select" required>
            <option value="">Pilih Operator</option>
            <option v-for="op in props.operators" :key="op.id" :value="op.id">
              {{ op.name }}
            </option>
          </select>
        </label>
        <label>
          Route
          <select
            v-model="form.route_id"
            class="select"
            required
            :disabled="!form.operator_id"
          >
            <option value="">Pilih Rute</option>
            <option
              v-for="route in filteredRoutes"
              :key="route.id"
              :value="route.id"
            >
              {{ route.code ? route.code + " - " : ""
              }}{{ route.origin_city }} → {{ route.destination_city }}
            </option>
          </select>
        </label>
        <label>
          Vehicle
          <select
            v-model="form.vehicle_id"
            class="select"
            required
            :disabled="!form.operator_id"
          >
            <option value="">Pilih Kendaraan</option>
            <option
              v-for="vehicle in vehicles"
              :key="vehicle.id"
              :value="vehicle.id"
            >
              {{ vehicle.plate_number }}
            </option>
          </select>
        </label>
        <label>
          Tanggal Keberangkatan
          <input
            v-model="form.departure_date"
            type="date"
            class="input"
            required
          />
        </label>
        <label>
          Jadwal Keberangkatan
          <input
            v-model="form.scheduled_time"
            type="time"
            class="input"
            required
          />
        </label>
        <label>
          Waktu Actual
          <input v-model="form.actual_time" type="time" class="input" />
        </label>
        <label>
          Jumlah Penumpang
          <input
            v-model="form.passengers"
            type="number"
            class="input"
            required
          />
        </label>
        <label>
          Kapasitas Kursi
          <input
            v-model="form.seat_capacity"
            type="number"
            class="input"
            required
            readonly
          />
        </label>
        <label>
          Okupansi (%)
          <input
            v-model="form.occupancy_rate"
            type="number"
            step="0.01"
            class="input"
            required
            readonly
          />
        </label>
        <label>
          Pendapatan
          <input
            v-model="form.revenue"
            type="number"
            step="0.01"
            class="input"
          />
        </label>
        <label>
          Nomor Gate
          <input v-model="form.gate_number" type="text" class="input" />
        </label>
        <label>
          Status
          <select v-model="form.status" class="input" required>
            <option value="scheduled">Scheduled</option>
            <option value="departed">Departed</option>
            <option value="cancelled">Cancelled</option>
            <option value="delayed">Delayed</option>
          </select>
        </label>
        <label>
          Catatan
          <textarea v-model="form.notes" class="input"></textarea>
        </label>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Simpan</button>
      </form>
      <button
        type="button"
        class="btn btn-secondary mt-6 px-4 py-2 rounded shadow text-white bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 transition-all duration-200 font-semibold border border-blue-700"
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
    </div>
  </div>
</template>

<script setup lang="ts">
function goToDashboard() {
  router.visit("/terminal/dashboard");
}
import { ref, watch, computed } from "vue";
import { router, useForm } from "@inertiajs/vue3";

interface Operator {
  id: number;
  name: string;
  // add other fields if needed
}
interface Route {
  id: number;
  operator_id: number;
  code?: string;
  origin_city?: string;
  destination_city?: string;
  // add other fields if needed
}

const props = defineProps<{
  terminal_name: string;
  routes: Route[];
  operators: Operator[];
}>();

interface Vehicle {
  id: number;
  plate_number: string;
  seat_capacity?: number;
  // add other fields if needed
}

const vehicles = ref<Vehicle[]>([]);
const filteredRoutes = computed(() => {
  if (!form.operator_id) return [];
  return props.routes.filter((r) => r.operator_id === Number(form.operator_id));
});

const routeOptions = computed(() => [
  { value: "", label: "Pilih Rute" },
  ...filteredRoutes.value.map((route) => ({
    value: route.id,
    label: `${route.code ? route.code + " - " : ""}${
      route.origin_city || ""
    } → ${route.destination_city || ""}`.trim(),
  })),
]);

const vehicleOptions = computed(() => [
  { value: "", label: "Pilih Kendaraan" },
  ...vehicles.value.map((vehicle) => ({
    value: vehicle.id,
    label: vehicle.plate_number || "",
  })),
]);

const form = useForm({
  // terminal_id is not needed for display, only for backend
  operator_id: "",
  route_id: "",
  vehicle_id: "",
  departure_date: "",
  scheduled_time: "",
  actual_time: "",
  passengers: "",
  seat_capacity: "",
  occupancy_rate: "",
  revenue: "",
  gate_number: "",
  status: "scheduled",
  notes: "",
});

watch(
  () => form.operator_id,
  async (operatorId) => {
    form.route_id = "";
    form.vehicle_id = "";
    if (operatorId) {
      const res = await fetch(`/terminal/vehicles/operator/${operatorId}`);
      vehicles.value = await res.json();
    } else {
      vehicles.value = [];
    }
  }
);

watch(
  () => form.vehicle_id,
  (vehicleId) => {
    const vehicle = vehicles.value.find((v) => v.id === Number(vehicleId));
    form.seat_capacity = vehicle && typeof vehicle.seat_capacity !== "undefined" ? String(vehicle.seat_capacity) : "";
  }
);

watch(
  [() => form.passengers, () => form.seat_capacity],
  ([passengers, seat_capacity]) => {
    const p = Number(passengers);
    const s = Number(seat_capacity);
    form.occupancy_rate = p && s ? ((p / s) * 100).toFixed(2) : "";
  }
);

function submit() {
  form.post(route("terminal.departures.store"), {
    onSuccess: () => {
      form.reset();
    },
  });
}
</script>

<style scoped>
.select {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  margin-top: 0.25rem;
  color: #111827; /* text-gray-900 */
  background: #f3f4f6; /* bg-gray-100 */
  font-size: 1rem;
  font-weight: 500;
}

::v-deep select,
::v-deep option {
  color: #111827 !important;
  background: #f3f4f6 !important;
}

/* Force select and option text color using ::v-deep for scoped styles */
::v-deep select,
::v-deep option {
  color: #1f2937 !important;
  background: #fff !important;
}
.input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  margin-top: 0.25rem;
  color: #1f2937; /* text-gray-800 for better contrast */
  background: #fff;
}

.select {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  margin-top: 0.25rem;
  color: #1f2937;
  background: #fff;
  font-size: 1rem;
  font-weight: 500;
}
.btn-primary {
  background: #2563eb;
  color: #fff;
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  border: none;
}
</style>
