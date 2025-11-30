<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TerminalRequest;
use App\Repositories\TerminalRepository;
use App\Services\TerminalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TerminalController extends Controller
{
    public function __construct(
        protected TerminalService $terminalService,
        protected TerminalRepository $terminalRepository
    ) {
        $this->middleware('permission:view terminals')->only(['index', 'show']);
        $this->middleware('permission:create terminals')->only(['create', 'store']);
        $this->middleware('permission:edit terminals')->only(['edit', 'update']);
        $this->middleware('permission:delete terminals')->only('destroy');
    }

    public function index(Request $request): Response
    {
        $search = $request->input('search');
        $terminals = $this->terminalRepository->getPaginatedWithSearch($search, 15);

        return Inertia::render('Admin/Terminals/Index', [
            'terminals' => $terminals,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Terminals/Create');
    }

    public function store(TerminalRequest $request): RedirectResponse
    {
        $this->terminalService->createTerminal($request->validated());

        return redirect()->route('admin.terminals.index')
            ->with('success', 'Terminal berhasil ditambahkan');
    }

    public function show(int $id): Response
    {
        $terminal = $this->terminalRepository->findOrFail($id);
        $dashboardData = $this->terminalService->getDashboardData($id);

        return Inertia::render('Admin/Terminals/Show', [
            'terminal' => $terminal,
            'data' => $dashboardData,
        ]);
    }

    public function edit(int $id): Response
    {
        $terminal = $this->terminalRepository->findOrFail($id);

        return Inertia::render('Admin/Terminals/Edit', [
            'terminal' => $terminal,
        ]);
    }

    public function update(TerminalRequest $request, int $id): RedirectResponse
    {
        $this->terminalService->updateTerminal($id, $request->validated());

        return redirect()->route('admin.terminals.index')
            ->with('success', 'Terminal berhasil diperbarui');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->terminalService->deleteTerminal($id);

        return redirect()->route('admin.terminals.index')
            ->with('success', 'Terminal berhasil dihapus');
    }
}
