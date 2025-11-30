<?php

namespace App\Repositories;

use App\Models\FinancialRecord;
use Illuminate\Database\Eloquent\Collection;

class FinancialRecordRepository extends BaseRepository
{
    /**
     * Get paginated records by terminal and date range
     */
    public function getPaginatedByTerminalAndDateRange(int $terminalId, string $startDate, string $endDate, int $perPage = 10)
    {
        return $this->model->where('terminal_id', $terminalId)
            ->dateRange($startDate, $endDate)
            ->orderBy('date', 'desc')
            ->paginate($perPage);
    }
    public function __construct(FinancialRecord $model)
    {
        parent::__construct($model);
    }

    /**
     * Get records by terminal and date range
     */
    public function getByTerminalAndDateRange(int $terminalId, string $startDate, string $endDate): Collection
    {
        return $this->model->where('terminal_id', $terminalId)
            ->dateRange($startDate, $endDate)
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * Get revenue summary
     */
    public function getRevenueSummary(int $terminalId, string $startDate, string $endDate): array
    {
        $records = $this->model->where('terminal_id', $terminalId)
            ->revenue()
            ->dateRange($startDate, $endDate)
            ->get();

        return [
            'total' => $records->sum('amount'),
            'by_category' => $records->groupBy('category')->map(fn($items) => $items->sum('amount')),
            'count' => $records->count(),
        ];
    }

    /**
     * Get expense summary
     */
    public function getExpenseSummary(int $terminalId, string $startDate, string $endDate): array
    {
        $records = $this->model->where('terminal_id', $terminalId)
            ->expense()
            ->dateRange($startDate, $endDate)
            ->get();

        return [
            'total' => $records->sum('amount'),
            'by_category' => $records->groupBy('category')->map(fn($items) => $items->sum('amount')),
            'count' => $records->count(),
        ];
    }

    /**
     * Get financial summary (revenue - expense)
     */
    public function getFinancialSummary(int $terminalId, string $startDate, string $endDate): array
    {
        $revenue = $this->getRevenueSummary($terminalId, $startDate, $endDate);
        $expense = $this->getExpenseSummary($terminalId, $startDate, $endDate);

        return [
            'revenue' => $revenue,
            'expense' => $expense,
            'net_income' => $revenue['total'] - $expense['total'],
        ];
    }

    /**
     * Get monthly trend
     */
    public function getMonthlyTrend(int $terminalId, int $year): array
    {
        $records = $this->model->where('terminal_id', $terminalId)
            ->whereYear('date', $year)
            ->get();

        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthRecords = $records->filter(fn($r) => $r->date->month === $month);
            
            $monthlyData[] = [
                'month' => $month,
                'revenue' => $monthRecords->where('type', 'revenue')->sum('amount'),
                'expense' => $monthRecords->where('type', 'expense')->sum('amount'),
            ];
        }

        return $monthlyData;
    }
}