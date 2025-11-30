<template>
  <div class="max-w-6xl mx-auto mt-10">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-3xl font-bold text-blue-700 tracking-tight">Data Keuangan Terminal</h2>
      <div class="flex gap-2">
        <button @click="goToCreate" class="btn btn-primary px-4 py-2 rounded shadow text-white bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 transition-all duration-200 font-semibold">
          + Tambah Data
        </button>
        <button
          type="button"
          class="btn btn-secondary px-4 py-2 rounded shadow text-white bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 transition-all duration-200 font-semibold"
          @click="goToDashboard"
        >
          Kembali ke Dashboard
        </button>
      </div>
    </div>
    <div class="mb-4 flex items-center gap-4">
      <div class="text-lg font-semibold text-blue-700">
        Terminal: {{ terminal?.name || '-' }}
      </div>
      <form @submit.prevent="applyDateFilter" class="flex items-center gap-2">
        <label class="text-sm text-gray-600">Tanggal:
          <input type="date" v-model="filterStart" class="input-date mx-2" />
        </label>
        <span class="text-gray-500">s/d</span>
        <input type="date" v-model="filterEnd" class="input-date mx-2" />
        <button type="submit" class="btn btn-primary px-3 py-1">Filter</button>
      </form>
    </div>
    <div class="overflow-x-auto bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gradient-to-r from-blue-100 to-blue-200">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-bold text-blue-700 uppercase">Tanggal</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-blue-700 uppercase">Tipe</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-blue-700 uppercase">Kategori</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-blue-700 uppercase">Deskripsi</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-blue-700 uppercase">Nominal</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-blue-700 uppercase">No. Referensi</th>
            <th class="px-4 py-3 text-center text-xs font-bold text-blue-700 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="record in (records?.data || [])" :key="record.id" class="hover:bg-blue-50 transition-all">
            <td class="px-4 py-3 whitespace-nowrap font-mono text-sm text-gray-700">{{ formatDate(record.date) }}</td>
            <td class="px-4 py-3 whitespace-nowrap capitalize text-blue-600 font-semibold">{{ record.type }}</td>
            <td class="px-4 py-3 whitespace-nowrap text-blue-500">{{ record.category }}</td>
            <td class="px-4 py-3 text-gray-700">{{ record.description }}</td>
            <td class="px-4 py-3 text-right font-bold text-green-700">Rp {{ Number(record.amount).toLocaleString('id-ID', {minimumFractionDigits:2}) }}</td>
            <td class="px-4 py-3 text-gray-500">{{ record.reference_number }}</td>
            <td class="px-4 py-3 text-center flex gap-2 justify-center">
              <button @click="goToEdit(record.id)" class="px-2 py-1 rounded bg-blue-600 text-white font-bold hover:bg-blue-700 transition-all">Edit</button>
              <button @click="deleteRecord(record.id)" class="px-2 py-1 rounded bg-red-600 text-white font-bold hover:bg-red-700 transition-all">Hapus</button>
            </td>
          </tr>
          <tr v-if="!records || records.data.length === 0">
            <td colspan="7" class="px-4 py-8 text-center text-gray-400">Tidak ada data keuangan ditemukan.</td>
          </tr>
        </tbody>
      </table>
      <!-- Pagination -->
      <div v-if="records && records.last_page > 1" class="flex justify-center items-center mt-8 gap-2">
        <button
          v-for="page in records.last_page"
          :key="page"
          :disabled="page === records.current_page"
          :class="['px-3 py-1 rounded border transition-all duration-150', page === records.current_page ? 'bg-blue-600 text-white font-bold border-blue-700 shadow' : 'bg-white text-blue-600 border-blue-200 hover:bg-blue-100']"
          @click="goToPage(page)"
        >
          {{ page }}
        </button>
      </div>
      <div class="flex items-center justify-end mt-4 gap-2">
        <label class="text-sm text-gray-600 flex items-center gap-2">
          Tampilkan
          <div class="relative w-20">
            <select v-model="perPage" @change="changePerPage" class="custom-select">
              <option v-for="n in [10, 20, 50, 100]" :key="n" :value="n">{{ n }}</option>
            </select>
            <svg class="absolute right-2 top-1/2 transform -translate-y-1/2 pointer-events-none w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </div>
          data per halaman
        </label>
      </div>
    </div>
    <div class="mt-10 bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-8 border border-blue-200 shadow">
      <h3 class="text-xl font-bold mb-6 text-blue-700 text-center">Ringkasan Keuangan</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="p-6 bg-white rounded-xl shadow flex items-center gap-4 border border-green-100">
          <div class="flex-shrink-0 w-14 h-14 flex items-center justify-center rounded-xl bg-green-50">
            <span style="font-size:2rem;">💰</span>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-sm text-gray-500 mb-1">Pendapatan</div>
            <div class="text-3xl font-extrabold text-green-700 break-words" style="line-height:1.2; word-break:break-all;">Rp {{ summary && summary.revenue && summary.revenue.total ? summary.revenue.total.toLocaleString('id-ID', {minimumFractionDigits:2}) : '0' }}</div>
          </div>
        </div>
        <div class="p-6 bg-white rounded-xl shadow text-center border border-red-100">
          <div class="text-sm text-gray-500 mb-2">Total Pengeluaran</div>
          <div class="text-2xl font-extrabold text-red-600">Rp {{ summary && summary.expense && summary.expense.total ? summary.expense.total.toLocaleString('id-ID', {minimumFractionDigits:2}) : '0' }}</div>
        </div>
        <div class="p-6 bg-white rounded-xl shadow text-center border border-blue-100">
          <div class="text-sm text-gray-500 mb-2">Pendapatan Bersih</div>
          <div class="text-2xl font-extrabold text-blue-700">Rp {{ summary && summary.net_income ? summary.net_income.toLocaleString('id-ID', {minimumFractionDigits:2}) : '0' }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
const props = defineProps({
  records: Object,
  summary: Object,
  filters: Object,
  terminal: Object
});
const { records, summary, filters, terminal } = props;
const perPage = ref(filters?.per_page || 10);
const filterStart = ref(filters?.start_date || '');
const filterEnd = ref(filters?.end_date || '');

function formatDate(dateStr) {
  if (!dateStr) return '';
  if (typeof dateStr === 'string' && dateStr.length >= 10) {
    // Convert YYYY-MM-DD to DD-MM-YYYY
    const [year, month, day] = dateStr.slice(0, 10).split('-');
    return `${day}-${month}-${year}`;
  }
  if (dateStr instanceof Date) {
    const iso = dateStr.toISOString().slice(0, 10);
    const [year, month, day] = iso.split('-');
    return `${day}-${month}-${year}`;
  }
  return dateStr;
}

function goToCreate() {
  router.visit('/terminal/financial/create');
}
function goToEdit(id) {
  router.visit(`/terminal/financial/${id}/edit`);
}
function deleteRecord(id) {
  if (confirm('Yakin ingin menghapus data keuangan ini?')) {
    router.delete(`/terminal/financial/${id}`, {
      onSuccess: () => {
        // Jika data di halaman habis, kembali ke halaman sebelumnya
        let page = records?.current_page || 1;
        if (records?.data?.length === 1 && page > 1) {
          page = page - 1;
        }
        router.get('/terminal/financial', { ...filters, page, per_page: perPage.value }, { preserveState: false, replace: false });
      },
    });
  }
}
function goToPage(page) {
  if (page !== records.current_page) {
    router.get('/terminal/financial', { ...filters, page, per_page: perPage.value }, { preserveState: false, replace: false });
  }
}
function changePerPage() {
  router.get('/terminal/financial', { ...filters, page: 1, per_page: perPage.value }, { preserveState: false, replace: false });
}
function goToDashboard() {
  router.visit('/terminal/dashboard');
}
function applyDateFilter() {
  router.get('/terminal/financial', {
    ...filters,
    start_date: filterStart.value,
    end_date: filterEnd.value,
    per_page: perPage.value,
    page: 1,
  }, { preserveState: false, replace: false });
}
</script>

<style scoped>
.btn-primary {
  background: #2563eb;
  color: #fff;
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  border: none;
}
.btn-secondary {
  background: linear-gradient(to right, #2563eb, #1e40af);
  color: #fff;
  border: none;
  border-radius: 0.375rem;
  font-weight: 600;
}
.custom-select {
  appearance: none;
  -webkit-appearance: none;
  background: none;
  width: 100%;
  padding-left: 1rem;
  padding-right: 2rem;
  padding-top: 0.5rem;
  padding-bottom: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.75rem;
  font-weight: 600;
  color: #374151;
  background-color: #fff;
  transition: box-shadow 0.2s;
}
.custom-select:focus {
  outline: none;
  box-shadow: 0 0 0 2px #2563eb33;
  border-color: #2563eb;
}
.input-date {
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  padding: 0.25rem 0.5rem;
  font-size: 1rem;
  color: #1f2937;
  background: #fff;
}
</style>
