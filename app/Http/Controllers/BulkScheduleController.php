<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\ScheduleAssignment;
use App\Models\ScheduleTemplate;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BulkScheduleController extends Controller {
    public function __construct() {
        $this->middleware('auth');
        // Removido authorizeResource para evitar conflictos con autorización manual
    }

    /**
     * Display a listing of bulk assignments.
     */
    public function index(Request $request): View {
        $this->authorize('manage_attendance');

        $query = ScheduleAssignment::with(['template', 'creator'])
            ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('search')) {
            $query->whereHas('template', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('end_date', '<=', $request->date_to);
        }

        $asignaciones = $query->paginate(15);

        return view('pages.schedules.bulk.index', compact('asignaciones'));
    }

    /**
     * Display the specified bulk assignment.
     */
    public function show(string $assignment): View {
        $this->authorize('manage_attendance');

        // Por ahora, mostrar vista básica - las asignaciones masivas no se persisten como entidades
        return view('pages.schedules.bulk.show', [
            'assignment_id' => $assignment,
            'asignacion' => null, // No hay modelo de asignación masiva
        ]);
    }

    /**
     * Show the form for bulk schedule assignment.
     */
    public function create(): View {
        $this->authorize('manage_attendance');

        $departamentos = Department::orderBy('name')->get();
        $equipos = Team::orderBy('name')->get();
        $empleados = Employee::with(['usuario', 'departamento', 'equipo'])
            ->orderBy('first_name')
            ->get();
        $plantillas = ScheduleTemplate::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.schedules.bulk.create', compact(
            'departamentos',
            'equipos',
            'empleados',
            'plantillas'
        ));
    }

    /**
     * Store bulk schedule assignments.
     */
    public function store(Request $request): RedirectResponse {
        $this->authorize('manage_attendance');

        $request->validate([
            'schedule_template_id' => 'required|exists:schedule_templates,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target_type' => 'required|in:departments,teams,employees',
            'target_ids' => 'required|array|min:1',
            'target_ids.*' => 'integer',
            'overwrite_existing' => 'boolean',
        ]);

        // Preparar filtros según el tipo de objetivo
        $targetFilters = [];
        switch ($request->target_type) {
            case 'departments':
                $targetFilters['departments'] = $request->target_ids;
                break;
            case 'teams':
                $targetFilters['teams'] = $request->target_ids;
                break;
            case 'employees':
                $targetFilters['employees'] = $request->target_ids;
                break;
        }

        // Ejecutar asignación masiva
        $resultado = app(\App\Actions\Horarios\AsignarHorariosMasivosAction::class)->handle([
            'schedule_template_id' => $request->schedule_template_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'target_filters' => $targetFilters,
            'overwrite_existing' => $request->boolean('overwrite_existing'),
        ]);

        $mensaje = "Se crearon {$resultado['creados']} horarios exitosamente.";

        if (!empty($resultado['conflictos'])) {
            $mensaje .= " Se encontraron " . count($resultado['conflictos']) . " conflictos que no se pudieron asignar.";
        }

        if (!empty($resultado['errores'])) {
            $mensaje .= " Ocurrieron " . count($resultado['errores']) . " errores durante el proceso.";
        }

        return redirect()
            ->route('schedules.index')
            ->with('success', $mensaje)
            ->with('resultado_asignacion', $resultado);
    }

    /**
     * Preview bulk assignment before execution.
     */
    public function preview(Request $request) {
        $this->authorize('manage_attendance');

        $request->validate([
            'schedule_template_id' => 'required|exists:schedule_templates,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target_type' => 'required|in:departments,teams,employees',
            'target_ids' => 'required|array|min:1',
        ]);

        // Calcular preview de la asignación
        $targetFilters = [];
        switch ($request->target_type) {
            case 'departments':
                $targetFilters['departments'] = $request->target_ids;
                break;
            case 'teams':
                $targetFilters['teams'] = $request->target_ids;
                break;
            case 'employees':
                $targetFilters['employees'] = $request->target_ids;
                break;
        }

        $action = app(\App\Actions\Horarios\AsignarHorariosMasivosAction::class);

        // Obtener empleados objetivo
        $empleados = $action->obtenerEmpleados($targetFilters);
        $fechas = $action->generarFechas($request->start_date, $request->end_date);
        $plantilla = ScheduleTemplate::findOrFail($request->schedule_template_id);

        // Calcular estadísticas del preview
        $totalHorarios = $empleados->count() * count($fechas);
        $totalDias = count($fechas);

        return response()->json([
            'empleados_count' => $empleados->count(),
            'fechas_count' => $totalDias,
            'horarios_total' => $totalHorarios,
            'plantilla' => [
                'name' => $plantilla->name,
                'start_time' => $plantilla->start_time,
                'end_time' => $plantilla->end_time,
            ],
            'empleados' => $empleados->map(function ($empleado) {
                return [
                    'id' => $empleado->id,
                    'name' => $empleado->first_name . ' ' . $empleado->last_name,
                    'department' => $empleado->departamento?->name,
                    'team' => $empleado->equipo?->name,
                ];
            }),
        ]);
    }

    /**
     * Show the import form.
     */
    public function importForm(): View {
        $this->authorize('manage_attendance');

        return view('pages.schedules.bulk-import');
    }

    /**
     * Import schedules from CSV.
     */
    public function import(Request $request): RedirectResponse {
        $this->authorize('manage_attendance');

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
        ]);

        // Aquí iría la lógica de importación CSV
        // Por ahora, solo redirigir con mensaje
        return redirect()
            ->route('schedules.bulk.index')
            ->with('info', 'Funcionalidad de importación CSV próximamente disponible.');
    }
}
