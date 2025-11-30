<?php

namespace App\Http\Controllers\Terminal;

use App\Http\Controllers\Controller;
use App\Services\TerminalService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        protected TerminalService $terminalService
    ) {}

    public function index(Request $request): Response
    {
        $user = $request->user();
        
        if (!$user->terminal_id) {
            abort(403, 'Anda tidak memiliki akses ke terminal manapun');
        }

        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $dashboardData = $this->terminalService->getDashboardData(
            $user->terminal_id,
            $startDate,
            $endDate
        );

        $analyticsData = $this->terminalService->getAnalyticsData(
            $user->terminal_id,
            $startDate,
            $endDate
        );

        return Inertia::render('Terminal/Dashboard', [
            'data' => $dashboardData,
            'analytics' => $analyticsData,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }
}