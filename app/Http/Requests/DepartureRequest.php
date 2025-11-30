<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create departures') || $this->user()->can('edit departures');
    }

    public function rules(): array
    {
        return [
            'terminal_id' => 'required|exists:terminals,id',
            'route_id' => 'required|exists:routes,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'operator_id' => 'required|exists:operators,id',
            'departure_date' => 'required|date',
            'scheduled_time' => 'required|date_format:H:i',
            'actual_time' => 'nullable|date_format:H:i',
            'passengers' => 'required|integer|min:0',
            'seat_capacity' => 'required|integer|min:1',
            'revenue' => 'nullable|numeric|min:0',
            'gate_number' => 'nullable|string|max:10',
            'status' => 'required|in:scheduled,departed,cancelled,delayed',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'terminal_id.required' => 'Terminal wajib dipilih',
            'route_id.required' => 'Rute wajib dipilih',
            'vehicle_id.required' => 'Kendaraan wajib dipilih',
            'operator_id.required' => 'Operator wajib dipilih',
            'departure_date.required' => 'Tanggal keberangkatan wajib diisi',
            'scheduled_time.required' => 'Jam keberangkatan wajib diisi',
            'passengers.required' => 'Jumlah penumpang wajib diisi',
            'passengers.min' => 'Jumlah penumpang minimal 0',
            'seat_capacity.required' => 'Kapasitas kursi wajib diisi',
            'status.required' => 'Status wajib dipilih',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->passengers > $this->seat_capacity) {
                $validator->errors()->add('passengers', 'Jumlah penumpang tidak boleh melebihi kapasitas kursi');
            }
        });
    }
}
