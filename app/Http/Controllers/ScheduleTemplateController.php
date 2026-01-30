<?php

namespace App\Http\Controllers;

use App\Models\ScheduleTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ScheduleTemplateController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of schedule templates.
     */
    public function index(): View {
        $this->authorize('manage_attendance');

        $plantillas = ScheduleTemplate::orderBy('name')->get();

        return view('pages.schedules.templates.index', compact('plantillas'));
    }

    /**
     * Show the form for creating a new schedule template.
     */
    public function create(): View {
        $this->authorize('manage_attendance');

        return view('pages.schedules.templates.create');
    }

    /**
     * Store a newly created schedule template in storage.
     */
    public function store(Request $request): RedirectResponse {
        $this->authorize('manage_attendance');

        $request->validate([
            'name' => 'required|string|max:255|unique:schedule_templates,name',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'lunch_duration_minutes' => 'nullable|integer|min:0|max:480',
            'break_duration_minutes' => 'nullable|integer|min:0|max:480',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        // Calcular duración total
        $start = \Carbon\Carbon::createFromFormat('H:i', $request->start_time);
        $end = \Carbon\Carbon::createFromFormat('H:i', $request->end_time);
        $totalMinutes = $start->diffInMinutes($end);

        $plantilla = ScheduleTemplate::create([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'lunch_duration_minutes' => $request->lunch_duration_minutes ?? 60,
            'break_duration_minutes' => $request->break_duration_minutes ?? 15,
            'total_duration_minutes' => $totalMinutes,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('schedule-templates.show', $plantilla)
            ->with('success', 'Plantilla creada exitosamente.');
    }

    /**
     * Display the specified schedule template.
     */
    public function show(ScheduleTemplate $scheduleTemplate): View {
        $this->authorize('manage_attendance');

        $scheduleTemplate->load('creador');

        // Obtener horarios que usan esta plantilla
        $horariosCount = $scheduleTemplate->horarios()->count();
        $horariosRecientes = $scheduleTemplate->horarios()
            ->with('empleado.usuario')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('pages.schedules.templates.show', compact(
            'scheduleTemplate',
            'horariosCount',
            'horariosRecientes'
        ));
    }

    /**
     * Show the form for editing the specified schedule template.
     */
    public function edit(ScheduleTemplate $scheduleTemplate): View {
        $this->authorize('manage_attendance');

        return view('pages.schedules.templates.edit', compact('scheduleTemplate'));
    }

    /**
     * Update the specified schedule template in storage.
     */
    public function update(Request $request, ScheduleTemplate $scheduleTemplate): RedirectResponse {
        $this->authorize('manage_attendance');

        $request->validate([
            'name' => 'required|string|max:255|unique:schedule_templates,name,' . $scheduleTemplate->id,
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'lunch_duration_minutes' => 'nullable|integer|min:0|max:480',
            'break_duration_minutes' => 'nullable|integer|min:0|max:480',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        // Recalcular duración total
        $start = \Carbon\Carbon::createFromFormat('H:i', $request->start_time);
        $end = \Carbon\Carbon::createFromFormat('H:i', $request->end_time);
        $totalMinutes = $start->diffInMinutes($end);

        $scheduleTemplate->update([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'lunch_duration_minutes' => $request->lunch_duration_minutes ?? 60,
            'break_duration_minutes' => $request->break_duration_minutes ?? 15,
            'total_duration_minutes' => $totalMinutes,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('schedule-templates.show', $scheduleTemplate)
            ->with('success', 'Plantilla actualizada exitosamente.');
    }

    /**
     * Remove the specified schedule template from storage.
     */
    public function destroy(ScheduleTemplate $scheduleTemplate): RedirectResponse {
        $this->authorize('manage_attendance');

        // Verificar si la plantilla está siendo usada
        if ($scheduleTemplate->horarios()->exists()) {
            return back()->withErrors([
                'error' => 'No se puede eliminar la plantilla porque está siendo utilizada por horarios existentes.'
            ]);
        }

        $scheduleTemplate->delete();

        return redirect()
            ->route('schedule-templates.index')
            ->with('success', 'Plantilla eliminada exitosamente.');
    }

    /**
     * Duplicate a schedule template.
     */
    public function duplicate(ScheduleTemplate $scheduleTemplate): RedirectResponse {
        $this->authorize('manage_attendance');

        $duplicada = ScheduleTemplate::create([
            'name' => $scheduleTemplate->name . ' (Copia)',
            'start_time' => $scheduleTemplate->start_time,
            'end_time' => $scheduleTemplate->end_time,
            'lunch_duration_minutes' => $scheduleTemplate->lunch_duration_minutes,
            'break_duration_minutes' => $scheduleTemplate->break_duration_minutes,
            'total_duration_minutes' => $scheduleTemplate->total_duration_minutes,
            'description' => $scheduleTemplate->description,
            'is_active' => false, // Las copias se crean inactivas por defecto
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('schedule-templates.edit', $duplicada)
            ->with('success', 'Plantilla duplicada exitosamente. Edítala según necesites.');
    }

    /**
     * Toggle active status of a template.
     */
    public function toggleActive(ScheduleTemplate $scheduleTemplate): RedirectResponse {
        $this->authorize('manage_attendance');

        $scheduleTemplate->update([
            'is_active' => !$scheduleTemplate->is_active
        ]);

        $estado = $scheduleTemplate->is_active ? 'activada' : 'desactivada';

        return back()->with('success', "Plantilla {$estado} exitosamente.");
    }
}
