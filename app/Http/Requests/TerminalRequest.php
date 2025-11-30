<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TerminalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create terminals') || $this->user()->can('edit terminals');
    }

    public function rules(): array
    {
        $terminalId = $this->route('terminal');

        return [
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('terminals', 'code')->ignore($terminalId),
            ],
            'name' => 'required|string|max:255',
            'type' => 'required|in:A,B,C',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'capacity' => 'required|integer|min:1',
            'boarding_gates' => 'required|integer|min:1',
            'parking_slots' => 'required|integer|min:1',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'status' => 'required|in:active,inactive,maintenance',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode terminal wajib diisi',
            'code.unique' => 'Kode terminal sudah digunakan',
            'name.required' => 'Nama terminal wajib diisi',
            'type.required' => 'Tipe terminal wajib dipilih',
            'type.in' => 'Tipe terminal tidak valid',
            'address.required' => 'Alamat wajib diisi',
            'city.required' => 'Kota wajib diisi',
            'province.required' => 'Provinsi wajib diisi',
            'capacity.required' => 'Kapasitas wajib diisi',
            'capacity.min' => 'Kapasitas minimal 1',
            'boarding_gates.required' => 'Jumlah jalur keberangkatan wajib diisi',
            'parking_slots.required' => 'Jumlah slot parkir wajib diisi',
            'status.required' => 'Status wajib dipilih',
        ];
    }
}