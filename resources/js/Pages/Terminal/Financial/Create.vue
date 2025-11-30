<template>
  <div class="max-w-xl mx-auto mt-10">
    <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
      <h2 class="text-2xl font-bold mb-6 text-blue-700 text-center">Tambah Data Keuangan</h2>
      <form @submit.prevent="submit">
        <div class="grid grid-cols-1 gap-6">
          <label>
            Tanggal
            <input v-model="form.date" type="date" class="input" required />
          </label>
          <label>
            Tipe
            <select v-model="form.type" class="select" required>
              <option value="">Pilih Tipe</option>
              <option value="revenue">Pemasukan</option>
              <option value="expense">Pengeluaran</option>
            </select>
          </label>
          <label>
            Kategori
            <select v-model="form.category" class="select" required>
              <option value="">Pilih Kategori</option>
              <option value="retribution">Retribusi</option>
              <option value="parking">Parkir</option>
              <option value="commercial">Komersial</option>
              <option value="operational">Operasional</option>
              <option value="maintenance">Pemeliharaan</option>
              <option value="utilities">Utilitas</option>
              <option value="salary">Gaji</option>
              <option value="other">Lainnya</option>
            </select>
          </label>
          <label>
            Deskripsi
            <input v-model="form.description" type="text" class="input" required />
          </label>
          <label>
            Nominal
            <input v-model="form.amount" type="number" step="0.01" class="input" required />
          </label>
          <label>
            No. Referensi
            <input v-model="form.reference_number" type="text" class="input" />
          </label>
        </div>
        <button type="submit" class="btn btn-primary mt-6 w-full">Simpan</button>
      </form>
      <button
        type="button"
        class="btn btn-secondary mt-4 w-full px-4 py-2 rounded shadow text-white bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 transition-all duration-200 font-semibold"
        @click="goToDashboard"
      >
        Kembali ke Dashboard
      </button>
    </div>
  </div>
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3';
const props = defineProps({ terminal_id: Number });
const form = useForm({
  terminal_id: props.terminal_id,
  date: '',
  type: '',
  category: '',
  description: '',
  amount: '',
  reference_number: '',
});
function submit() {
  form.post('/terminal/financial', {
    onSuccess: () => router.visit('/terminal/financial'),
  });
}
function goToDashboard() {
  router.visit('/terminal/dashboard');
}
</script>

<style scoped>
.input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  margin-top: 0.25rem;
  color: #1f2937;
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
.btn-secondary {
  background: linear-gradient(to right, #2563eb, #1e40af);
  color: #fff;
  border: none;
  border-radius: 0.375rem;
  font-weight: 600;
}
</style>
