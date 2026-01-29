<?php

namespace App\Http\Controllers;

use App\Actions\Empleados\CrearEmpleadoAction;
use App\Actions\Empleados\ActualizarEmpleadoAction;
use App\Actions\Empleados\DesactivarEmpleadoAction;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeesImport;
use App\Exports\EmployeesExport;

class EmployeeManagementController extends Controller {
    public function __construct() {
    }

    private function verificarRolAnalistaWfm(): void {
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }
    }

    public function index(Request $request): View {
        $this->verificarRolAnalistaWfm();

        $query = Employee::with(['departamento', 'supervisor', 'usuario']);

        // Filtros
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%')
                    ->orWhere('employee_number', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->filled('position')) {
            $query->where('position', 'like', '%' . $request->position . '%');
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('deleted_at');
            } elseif ($request->status === 'inactive') {
                $query->onlyTrashed();
            }
        }

        $empleados = $query->paginate(15);
        $departamentos = Department::orderBy('name')->get();

        return view('pages.admin.employees.index', compact('empleados', 'departamentos'));
    }

    public function create(): View {
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        $departamentos = Department::orderBy('name')->get();
        $supervisores = Employee::whereNull('deleted_at')
            ->whereNotNull('user_id')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('pages.admin.employees.create', compact('departamentos', 'supervisores'));
    }

    public function store(CreateEmployeeRequest $request, CrearEmpleadoAction $action): RedirectResponse {
        $this->verificarRolAnalistaWfm();

        $empleado = $action->handle($request->validated());

        return redirect()->route('admin.employees.index')
            ->with('success', 'Empleado creado correctamente.');
    }

    public function show(int $employeeId): View {
        $this->verificarRolAnalistaWfm();

        $empleado = Employee::with(['departamento', 'supervisor', 'usuario', 'subordinados'])
            ->findOrFail($employeeId);

        return view('pages.admin.employees.show', compact('empleado'));
    }

    public function edit(int $employeeId): View {
        $this->verificarRolAnalistaWfm();

        $empleado = Employee::with(['departamento', 'supervisor', 'usuario'])
            ->findOrFail($employeeId);

        $departamentos = Department::orderBy('name')->get();
        $supervisores = Employee::where('id', '!=', $empleado->id)
            ->whereNull('deleted_at')
            ->whereNotNull('user_id')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('pages.admin.employees.edit', compact('empleado', 'departamentos', 'supervisores'));
    }

    public function update(UpdateEmployeeRequest $request, int $employeeId, ActualizarEmpleadoAction $action): RedirectResponse {
        $this->verificarRolAnalistaWfm();

        $action->handle($employeeId, $request->validated());

        return redirect()->route('admin.employees.index')
            ->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy(int $employeeId, DesactivarEmpleadoAction $action): RedirectResponse {
        $this->verificarRolAnalistaWfm();

        $action->handle($employeeId);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Empleado desactivado correctamente.');
    }

    public function restore(int $id): RedirectResponse {
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        $empleado = Employee::withTrashed()->findOrFail($id);
        $empleado->restore();

        return redirect()->route('admin.employees.index')
            ->with('success', 'Empleado reactivado correctamente.');
    }

    public function import(Request $request): RedirectResponse {
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new EmployeesImport, $request->file('file'));

            return redirect()->route('admin.employees.index')
                ->with('success', 'Empleados importados correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('admin.employees.index')
                ->with('error', 'Error al importar empleados: ' . $e->getMessage());
        }
    }

    public function export(Request $request): JsonResponse {
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        $filename = 'empleados_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new EmployeesExport($request->all()), $filename);
    }
}
