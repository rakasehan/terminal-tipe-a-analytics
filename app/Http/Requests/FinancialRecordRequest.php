<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinancialRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create financials') || $this->user()->can('edit financials');
    }

    public function rules(): array
    {
        return [
            'terminal_id' => 'required|exists:terminals,id',
            'date' => 'required|date',
            'type' => 'required|in:revenue,expense',
            'category' => 'required|in:retribution,parking,commercial,operational,maintenance,utilities,salary,other',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'reference_number' => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'terminal_id.required' => 'Terminal wajib dipilih',
            'date.required' => 'Tanggal wajib diisi',
            'type.required' => 'Tipe transaksi wajib dipilih',
            'category.required' => 'Kategori wajib dipilih',
            'description.required' => 'Deskripsi wajib diisi',
            'amount.required' => 'Jumlah wajib diisi',
            'amount.min' => 'Jumlah minimal 0',
        ];
    }
}