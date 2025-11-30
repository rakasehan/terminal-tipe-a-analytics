<?php

namespace App\Services;

use App\Repositories\TerminalRepository;
use App\Repositories\DepartureRepository;
use App\Repositories\FinancialRecordRepository;
use App\Repositories\PassengerStatisticRepository;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportService
{
    public function __construct(
        protected TerminalRepository $terminalRepository,
        protected DepartureRepository $departureRepository,
        protected FinancialRecordRepository $financialRecordRepository,
        protected PassengerStatisticRepository $passengerStatisticRepository
    ) {}

    /**
     * Generate terminal report
     */
    public function generateTerminalReport(int $terminalId, string $startDate, string $endDate, string $format = 'pdf'): mixed
    {
        $data = $this->getReportData($terminalId, $startDate, $endDate);

        if ($format === 'excel') {
            return $this->exportToExcel($data);
        }

        return $this->exportToPdf($data);
    }

    /**
     * Get report data
     */
    protected function getReportData(int $terminalId, string $startDate, string $endDate): array
    {
        $terminal = $this->terminalRepository->findOrFail($terminalId);
        
        $statistics = $this->terminalRepository->getStatistics($terminalId, $startDate, $endDate);
        
        $departures = $this->departureRepository->getByTerminal($terminalId, $startDate, $endDate);
        
        $financialSummary = $this->financialRecordRepository->getFinancialSummary($terminalId, $startDate, $endDate);
        
        $passengerStats = $this->passengerStatisticRepository->getByTerminalAndDateRange($terminalId, $startDate, $endDate);

        return [
            'terminal' => $terminal,
            'statistics' => $statistics,
            'departures' => $departures,
            'financial_summary' => $financialSummary,
            'passenger_stats' => $passengerStats,
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Export to PDF
     */
    protected function exportToPdf(array $data): \Illuminate\Http\Response
    {
        $pdf = Pdf::loadView('reports.terminal', $data);
        
        $filename = sprintf(
            'terminal_report_%s_%s.pdf',
            $data['terminal']->code,
            now()->format('YmdHis')
        );

        return $pdf->download($filename);
    }

    /**
     * Export to Excel
     */
    protected function exportToExcel(array $data): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $filename = sprintf(
            'terminal_report_%s_%s.xlsx',
            $data['terminal']->code,
            now()->format('YmdHis')
        );

        return Excel::download(
            new \App\Exports\TerminalReportExport($data),
            $filename
        );
    }

    /**
     * Generate multi-terminal comparison report (for BPTD Admin)
     */
    public function generateComparisonReport(array $terminalIds, string $startDate, string $endDate): array
    {
        $reports = [];

        foreach ($terminalIds as $terminalId) {
            $reports[] = $this->getReportData($terminalId, $startDate, $endDate);
        }

        return [
            'terminals' => $reports,
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'comparison' => $this->calculateComparison($reports),
        ];
    }

    /**
     * Calculate comparison metrics
     */
    protected function calculateComparison(array $reports): array
    {
        return [
            'total_passengers' => collect($reports)->sum('statistics.total_passengers'),
            'total_departures' => collect($reports)->sum('statistics.total_departures'),
            'total_revenue' => collect($reports)->sum('financial_summary.revenue.total'),
            'average_occupancy' => collect($reports)->avg('statistics.average_occupancy'),
        ];
    }
}