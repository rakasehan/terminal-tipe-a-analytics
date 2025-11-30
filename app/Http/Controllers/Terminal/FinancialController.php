<?php

namespace App\Http\Controllers\Terminal;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinancialRecordRequest;
use App\Repositories\FinancialRecordRepository;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class FinancialController extends Controller
{
    public function __construct(
        protected FinancialRecordRepository $financialRecordRepository
    ) {
        $this->middleware('permission:view financials')->only(['index']);
        $this->middleware('permission:create financials')->only(['create', 'store']);
        $this->middleware('permission:edit financials')->only(['edit', 'update']);
        $this->middleware('permission:delete financials')->only('destroy');
    }

    public function index(Request $request): Response
    {
        $user = $request->user();
        
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $records = $this->financialRecordRepository->getByTerminalAndDateRange(
            $user->terminal_id,
            $startDate,
            $endDate
        );

        $summary = $this->financialRecordRepository->getFinancialSummary(
            $user->terminal_id,
            $startDate,
            $endDate
        );

        return Inertia::render('Terminal/Financial/Index', [
            'records' => $records,
            'summary' => $summary,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Terminal/Financial/Create', [
            'terminal_id' => $request->user()->terminal_id,
        ]);
    }

    public function store(FinancialRecordRequest $request): RedirectResponse
    {
        $this->financialRecordRepository->create($request->validated());

        return redirect()->route('terminal.financial.index')
            ->with('success', 'Data keuangan berhasil ditambahkan');
    }

    public function edit(int $id, Request $request): Response
    {
        $record = $this->financialRecordRepository->findOrFail($id);
        
        if ($record->terminal_id !== $request->user()->terminal_id) {
            abort(403);
        }

        return Inertia::render('Terminal/Financial/Edit', [
            'record' => $record,
        ]);
    }

    public function update(FinancialRecordRequest $request, int $id): RedirectResponse
    {
        $record = $this->financialRecordRepository->findOrFail($id);
        
        if ($record->terminal_id !== $request->user()->terminal_id) {
            abort(403);
        }

        $this->financialRecordRepository->update($id, $request->validated());

        return redirect()->route('terminal.financial.index')
            ->with('success', 'Data keuangan berhasil diperbarui');
    }

    public function destroy(int $id, Request $request): RedirectResponse
    {
        $record = $this->financialRecordRepository->findOrFail($id);
        
        if ($record->terminal_id !== $request->user()->terminal_id) {
            abort(403);
        }

        $this->financialRecordRepository->delete($id);

        return redirect()->route('terminal.financial.index')
            ->with('success', 'Data keuangan berhasil dihapus');
    }
}