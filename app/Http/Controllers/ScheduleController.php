<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\ScheduleTemplate;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of schedules.
     */
    public function index(Request $request): View {
        $this->authorize('view_all_attendance'); // Reutilizamos el permiso de asistencia

        $query = Schedule::with(['empleado.usuario', 'plantilla', 'creador'])
            ->orderBy('date', 'desc')
            ->orderBy('scheduled_start', 'asc');

        // Filtros
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('department_id')) {
            $query->whereHas('empleado', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $horarios = $query->paginate(15);

        // Datos para filtros
        $empleados = Employee::with('usuario')->orderBy('first_name')->get();
        $departamentos = Department::orderBy('name')->get();
        $equipos = Team::orderBy('name')->get();

        return view('pages.schedules.index', compact(
            'horarios',
            'empleados',
            'departamentos',
            'equipos'
        ));
    }

    /**
     * Show the form for creating a new schedule.
     */
    public function create(): View {
        $this->authorize('manage_attendance');

        $empleados = Employee::with(['usuario', 'departamento', 'equipo'])
            ->orderBy('first_name')
            ->get();

        $plantillas = ScheduleTemplate::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.schedules.create', compact('empleados', 'plantillas'));
    }

    /**
     * Store a newly created schedule in storage.
     */
    public function store(Request $request): RedirectResponse {
        $this->authorize('manage_attendance');

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date|after_or_equal:today',
            'scheduled_start' => 'required|date_format:H:i',
            'scheduled_end' => 'required|date_format:H:i|after:scheduled_start',
            'schedule_template_id' => 'nullable|exists:schedule_templates,id',
            'notes' => 'nullable|string|max:500',
        ]);

        // Combinar fecha con hora
        $scheduledStart = $request->date . ' ' . $request->scheduled_start . ':00';
        $scheduledEnd = $request->date . ' ' . $request->scheduled_end . ':00';

        // Validar el horario
        $validacion = app(\App\Actions\Horarios\ValidarHorarioAction::class)->handle([
            'employee_id' => $request->employee_id,
            'date' => $request->date,
            'scheduled_start' => $scheduledStart,
            'scheduled_end' => $scheduledEnd,
        ]);

        if (!empty($validacion)) {
            return back()
                ->withErrors($validacion)
                ->withInput();
        }

        // Crear el horario
        $horario = Schedule::create([
            'employee_id' => $request->employee_id,
            'schedule_template_id' => $request->schedule_template_id,
            'date' => $request->date,
            'scheduled_start' => $scheduledStart,
            'scheduled_end' => $scheduledEnd,
            'status' => 'scheduled',
            'is_published' => false,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('schedules.show', $horario)
            ->with('success', 'Horario creado exitosamente.');
    }

    /**
     * Display the specified schedule.
     */
    public function show(Schedule $schedule): View {
        $this->authorize('view', $schedule);

        $schedule->load([
            'empleado.usuario',
            'plantilla',
            'actividades',
            'registroAsistencia',
            'conflictos'
        ]);

        return view('pages.schedules.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified schedule.
     */
    public function edit(Schedule $schedule): View {
        $this->authorize('update', $schedule);

        $empleados = Employee::with(['usuario', 'departamento', 'equipo'])
            ->orderBy('first_name')
            ->get();

        $plantillas = ScheduleTemplate::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.schedules.edit', compact('schedule', 'empleados', 'plantillas'));
    }

    /**
     * Update the specified schedule in storage.
     */
    public function update(Request $request, Schedule $schedule): RedirectResponse {
        $this->authorize('update', $schedule);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'scheduled_start' => 'required|date_format:H:i',
            'scheduled_end' => 'required|date_format:H:i|after:scheduled_start',
            'schedule_template_id' => 'nullable|exists:schedule_templates,id',
            'status' => 'required|in:scheduled,present,absent,late,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        // Combinar fecha con hora
        $scheduledStart = $request->date . ' ' . $request->scheduled_start . ':00';
        $scheduledEnd = $request->date . ' ' . $request->scheduled_end . ':00';

        // Validar el horario
        $validacion = app(\App\Actions\Horarios\ValidarHorarioAction::class)->handle([
            'id' => $schedule->id,
            'employee_id' => $request->employee_id,
            'date' => $request->date,
            'scheduled_start' => $scheduledStart,
            'scheduled_end' => $scheduledEnd,
        ]);

        if (!empty($validacion)) {
            return back()
                ->withErrors($validacion)
                ->withInput();
        }

        // Actualizar el horario
        $schedule->update([
            'employee_id' => $request->employee_id,
            'schedule_template_id' => $request->schedule_template_id,
            'date' => $request->date,
            'scheduled_start' => $scheduledStart,
            'scheduled_end' => $scheduledEnd,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()
            ->route('schedules.show', $schedule)
            ->with('success', 'Horario actualizado exitosamente.');
    }

    /**
     * Remove the specified schedule from storage.
     */
    public function destroy(Schedule $schedule): RedirectResponse {
        $this->authorize('delete', $schedule);

        $schedule->delete();

        return redirect()
            ->route('schedules.index')
            ->with('success', 'Horario eliminado exitosamente.');
    }

    /**
     * Publish a schedule.
     */
    public function publish(Schedule $schedule): RedirectResponse {
        $this->authorize('manage_attendance');

        $schedule->publish();

        return back()->with('success', 'Horario publicado exitosamente.');
    }

    /**
     * Unpublish a schedule.
     */
    public function unpublish(Schedule $schedule): RedirectResponse {
        $this->authorize('manage_attendance');

        $schedule->unpublish();

        return back()->with('success', 'Horario despublicado exitosamente.');
    }

    /**
     * Show today's schedules.
     */
    public function today(): View {
        $this->authorize('view_all_attendance');

        $horarios = Schedule::whereDate('date', today())
            ->with(['empleado.usuario', 'plantilla', 'conflictos'])
            ->orderBy('scheduled_start')
            ->get();

        return view('pages.schedules.today', compact('horarios'));
    }
}
