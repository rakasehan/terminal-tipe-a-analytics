<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TerminalService;
use App\Repositories\TerminalRepository;
use App\Repositories\DepartureRepository;
use App\Repositories\FinancialRecordRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        protected TerminalService $terminalService,
        protected TerminalRepository $terminalRepository,
        protected DepartureRepository $departureRepository,
        protected FinancialRecordRepository $financialRecordRepository
    ) {}

    public function index(Request $request): Response
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $terminals = $this->terminalRepository->getActive();

        // Aggregate statistics across all terminals
        $totalStatistics = [
            'total_terminals' => $terminals->count(),
            'total_departures' => 0,
            'total_passengers' => 0,
            'total_revenue' => 0,
            'average_occupancy' => 0,
        ];

        $terminalData = [];
        foreach ($terminals as $terminal) {
            $stats = $this->terminalRepository->getStatistics($terminal->id, $startDate, $endDate);
            
            $totalStatistics['total_departures'] += $stats['total_departures'];
            $totalStatistics['total_passengers'] += $stats['total_passengers'];
            $totalStatistics['total_revenue'] += $stats['total_revenue'];
            
            $terminalData[] = [
                'terminal' => $terminal,
                'statistics' => $stats,
            ];
        }

        $totalStatistics['average_occupancy'] = $terminals->count() > 0 
            ? collect($terminalData)->avg('statistics.average_occupancy') 
            : 0;

        // Get recent activities
        $recentDepartures = $this->departureRepository->getPaginatedWithFilters([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ], 10);

        return Inertia::render('Admin/Dashboard', [
            'statistics' => $totalStatistics,
            'terminals' => $terminalData,
            'recentDepartures' => $recentDepartures,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }
}