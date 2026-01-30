<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\Schedule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller {
    use AuthorizesRequests;
    public function index() {
        $this->authorize('view_all_attendance');

        $attendanceLogs = AttendanceLog::with(['employee.usuario', 'schedule'])
            ->orderBy('check_in_time', 'desc')
            ->paginate(20);

        return view('pages.attendance.index', compact('attendanceLogs'));
    }

    public function show($id) {
        $attendanceLog = AttendanceLog::with(['employee.usuario', 'schedule'])
            ->findOrFail($id);

        $this->authorize('view_attendance', $attendanceLog);

        return view('pages.attendance.show', compact('attendanceLog'));
    }

    public function markAttendance(Request $request) {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return redirect()->back()->with('error', 'No se encontró información de empleado.');
        }

        // Buscar horario del día actual
        $today = now()->toDateString();
        $schedule = Schedule::where('employee_id', $employee->id)
            ->where('date', $today)
            ->first();

        if (!$schedule) {
            return redirect()->back()->with('error', 'No tiene horario asignado para hoy.');
        }

        // Verificar si ya marcó entrada hoy
        $existingAttendance = AttendanceLog::where('employee_id', $employee->id)
            ->whereDate('check_in_time', $today)
            ->first();

        if ($request->has('check_out') && $existingAttendance) {
            // Marcar salida
            $existingAttendance->update([
                'check_out_time' => now(),
                'worked_minutes' => $existingAttendance->check_in_time->diffInMinutes(now()),
            ]);

            return redirect()->back()->with('success', 'Salida registrada exitosamente.');
        }

        if (!$existingAttendance) {
            // Marcar entrada
            $lateMinutes = 0;
            $status = 'present';

            if (now()->greaterThan($schedule->scheduled_start)) {
                $lateMinutes = $schedule->scheduled_start->diffInMinutes(now());
                $status = $lateMinutes > 10 ? 'late' : 'present'; // Tolerancia de 10 minutos
            }

            AttendanceLog::create([
                'employee_id' => $employee->id,
                'schedule_id' => $schedule->id,
                'check_in_time' => now(),
                'status' => $status,
                'late_minutes' => $lateMinutes,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->back()->with('success', 'Entrada registrada exitosamente.');
        }

        return redirect()->back()->with('info', 'Ya registró entrada hoy. Puede marcar salida.');
    }

    public function myAttendance() {
        $employee = Auth::user()->empleado;

        if (!$employee) {
            abort(403, 'No tienes un perfil de empleado asociado.');
        }

        $attendanceLogs = AttendanceLog::where('employee_id', $employee->id)
            ->orderBy('check_in_time', 'desc')
            ->paginate(15);

        return view('pages.attendance.my-attendance', compact('attendanceLogs'));
    }

    public function teamAttendance() {
        $this->authorize('view_team_attendance');

        $user = Auth::user();
        $teamMembers = collect();

        if ($user->hasRole('coordinador')) {
            // Coordinador ve su equipo
            $teamMembers = Employee::where('supervisor_id', $user->id)->get();
        } elseif ($user->hasRole('jefe')) {
            // Jefe ve todos los empleados de sus departamentos
            $departments = $user->departments ?? [];
            $teamMembers = Employee::whereIn('department_id', $departments->pluck('id'))->get();
        }

        $attendanceLogs = AttendanceLog::whereIn('employee_id', $teamMembers->pluck('id'))
            ->whereDate('check_in_time', today())
            ->with(['employee.usuario'])
            ->get();

        return view('pages.attendance.team', compact('attendanceLogs', 'teamMembers'));
    }

    public function todayAttendance() {
        $this->authorize('view_all_attendance');

        $attendanceLogs = AttendanceLog::whereDate('check_in_time', today())
            ->with(['empleado.usuario', 'schedule'])
            ->orderBy('check_in_time', 'desc')
            ->get();

        return view('pages.attendance.today', compact('attendanceLogs'));
    }

    public function create() {
        $this->authorize('manage_attendance');

        $employees = Employee::with('usuario')->get();

        return view('pages.attendance.create', compact('employees'));
    }

    public function store(Request $request) {
        $this->authorize('manage_attendance');

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'check_in_time' => 'required|date',
            'check_out_time' => 'nullable|date|after:check_in_time',
            'status' => 'required|in:present,late,absent,justified,partial',
            'notes' => 'nullable|string|max:500',
        ]);

        $employee = Employee::findOrFail($request->employee_id);

        // Buscar horario correspondiente
        $schedule = Schedule::where('employee_id', $employee->id)
            ->where('date', Carbon::parse($request->check_in_time)->toDateString())
            ->first();

        $lateMinutes = 0;
        if ($schedule && $request->status === 'late') {
            $scheduledStart = Carbon::parse($schedule->scheduled_start);
            $actualStart = Carbon::parse($request->check_in_time);
            $lateMinutes = $scheduledStart->diffInMinutes($actualStart);
        }

        $workedMinutes = null;
        if ($request->check_out_time) {
            $checkIn = Carbon::parse($request->check_in_time);
            $checkOut = Carbon::parse($request->check_out_time);
            $workedMinutes = $checkIn->diffInMinutes($checkOut);
        }

        AttendanceLog::create([
            'employee_id' => $request->employee_id,
            'schedule_id' => $schedule?->id,
            'check_in_time' => $request->check_in_time,
            'check_out_time' => $request->check_out_time,
            'status' => $request->status,
            'late_minutes' => $lateMinutes,
            'worked_minutes' => $workedMinutes,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('attendance.index')->with('success', 'Registro de asistencia creado exitosamente.');
    }
}
