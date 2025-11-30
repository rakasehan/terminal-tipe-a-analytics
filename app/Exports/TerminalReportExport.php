<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TerminalReportExport implements WithMultipleSheets
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            new DeparturesSheet($this->data['departures']),
            new StatisticsSheet($this->data['statistics']),
            new FinancialSheet($this->data['financial_summary']),
        ];
    }
}

class DeparturesSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $departures;

    public function __construct($departures)
    {
        $this->departures = $departures;
    }

    public function collection()
    {
        return $this->departures;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Rute',
            'Operator',
            'Kendaraan',
            'Penumpang',
            'Kapasitas',
            'Okupansi (%)',
            'Pendapatan',
            'Status',
        ];
    }

    public function map($departure): array
    {
        return [
            $departure->departure_date,
            $departure->route->full_name ?? '-',
            $departure->operator->name ?? '-',
            $departure->vehicle->plate_number ?? '-',
            $departure->passengers,
            $departure->seat_capacity,
            $departure->occupancy_rate,
            $departure->revenue,
            $departure->status,
        ];
    }

    public function title(): string
    {
        return 'Keberangkatan';
    }
}

class StatisticsSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $statistics;

    public function __construct($statistics)
    {
        $this->statistics = $statistics;
    }

    public function collection()
    {
        return collect([
            [
                'Metric' => 'Total Keberangkatan',
                'Value' => $this->statistics['total_departures'],
            ],
            [
                'Metric' => 'Total Penumpang',
                'Value' => $this->statistics['total_passengers'],
            ],
            [
                'Metric' => 'Total Pendapatan',
                'Value' => $this->statistics['total_revenue'],
            ],
            [
                'Metric' => 'Rata-rata Okupansi (%)',
                'Value' => round($this->statistics['average_occupancy'], 2),
            ],
        ]);
    }

    public function headings(): array
    {
        return ['Metric', 'Value'];
    }

    public function title(): string
    {
        return 'Statistik';
    }
}

class FinancialSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $financial;

    public function __construct($financial)
    {
        $this->financial = $financial;
    }

    public function collection()
    {
        return collect([
            [
                'Type' => 'Revenue',
                'Total' => $this->financial['revenue']['total'],
            ],
            [
                'Type' => 'Expense',
                'Total' => $this->financial['expense']['total'],
            ],
            [
                'Type' => 'Net Income',
                'Total' => $this->financial['net_income'],
            ],
        ]);
    }

    public function headings(): array
    {
        return ['Type', 'Total'];
    }

    public function title(): string
    {
        return 'Keuangan';
    }
}