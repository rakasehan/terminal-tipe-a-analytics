<?php

namespace App\Http\Controllers\Terminal;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartureRequest;
use App\Models\Operator;
use App\Models\Route;
use App\Models\Vehicle;
use App\Repositories\DepartureRepository;
use App\Services\DepartureService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DepartureController extends Controller
{
    public function __construct(
        protected DepartureService $departureService,
        protected DepartureRepository $departureRepository
    ) {
        $this->middleware('permission:view departures')->only(['index', 'show']);
        $this->middleware('permission:create departures')->only(['create', 'store']);
        $this->middleware('permission:edit departures')->only(['edit', 'update']);
        $this->middleware('permission:delete departures')->only('destroy');
    }

    public function index(Request $request): Response
    {
        $user = $request->user();

        $filters = [
            'terminal_id' => $user->terminal_id,
            'route_id' => $request->input('route_id'),
            'status' => $request->input('status'),
            'start_date' => $request->input('start_date', now()->format('Y-m-d')),
            'end_date' => $request->input('end_date', now()->format('Y-m-d')),
            'search' => $request->input('search'),
            'per_page' => $request->input('per_page', 10),
        ];

        $departures = $this->departureService->getDeparturesWithFilters($filters, $filters['per_page']);

        // Only send safe user info
        $safeUser = [
            'id' => $user->id,
            'name' => $user->name,
            'roles' => $user->roles->pluck('name'),
            'terminal_id' => $user->terminal_id,
        ];

        return Inertia::render('Terminal/Departures/Index', [
            'departures' => $departures,
            'filters' => $filters,
            'user' => $safeUser,
        ]);
    }

    public function create(Request $request): Response
    {
        $routes = Route::active()->with('operator')->get();
        $operators = Operator::active()->get();

        $terminal = $request->user()->terminal;

        return Inertia::render('Terminal/Departures/Create', [
            'terminal_name' => $terminal ? $terminal->name : '',
            'routes' => $routes,
            'operators' => $operators,
        ]);
    }

    public function store(DepartureRequest $request): RedirectResponse
    {
        $this->departureService->createDeparture($request->validated());

        return redirect()->route('terminal.departures.index')
            ->with('success', 'Data keberangkatan berhasil ditambahkan');
    }

    public function edit(int $id, Request $request): Response
    {
        $departure = $this->departureRepository->findOrFail($id);

        // Ensure user can only edit departures from their terminal
        if ($departure->terminal_id !== $request->user()->terminal_id) {
            abort(403);
        }

        $routes = Route::active()->with('operator')->get();
        $operators = Operator::active()->get();

        $terminal = $request->user()->terminal;

        return Inertia::render('Terminal/Departures/Edit', [
            'departure' => $departure->load(['route', 'vehicle', 'operator']),
            'terminal_name' => $terminal ? $terminal->name : '',
            'routes' => $routes,
            'operators' => $operators,
        ]);
    }

    public function update(DepartureRequest $request, int $id): RedirectResponse
    {
        $departure = $this->departureRepository->findOrFail($id);

        if ($departure->terminal_id !== $request->user()->terminal_id) {
            abort(403);
        }

        $this->departureService->updateDeparture($id, $request->validated());

        return redirect()->route('terminal.departures.index')
            ->with('success', 'Data keberangkatan berhasil diperbarui');
    }

    public function destroy(int $id, Request $request): RedirectResponse
    {
        $departure = $this->departureRepository->findOrFail($id);

        if ($departure->terminal_id !== $request->user()->terminal_id) {
            abort(403);
        }

        $this->departureService->deleteDeparture($id);

        return redirect()->route('terminal.departures.index')
            ->with('success', 'Data keberangkatan berhasil dihapus');
    }

    public function getVehiclesByOperator(int $operatorId)
    {
        $vehicles = Vehicle::where('operator_id', $operatorId)
            ->where('status', 'active')
            ->get();

        return response()->json($vehicles);
    }
}
