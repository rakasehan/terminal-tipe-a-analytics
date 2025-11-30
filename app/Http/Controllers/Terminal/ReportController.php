<?php

namespace App\Http\Controllers\Terminal;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService
    ) {
        $this->middleware('permission:view reports')->only('index');
        $this->middleware('permission:export reports')->only('export');
    }

    public function index(Request $request): Response
    {
        return Inertia::render('Terminal/Reports/Index', [
            'terminal_id' => $request->user()->terminal_id,
        ]);
    }

    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,excel',
        ]);

        $user = $request->user();

        return $this->reportService->generateTerminalReport(
            $user->terminal_id,
            $request->start_date,
            $request->end_date,
            $request->format
        );
    }
}