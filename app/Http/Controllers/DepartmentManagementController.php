<?php

namespace App\Http\Controllers;

use App\Actions\Departamentos\CrearDepartamentoAction;
use App\Actions\Departamentos\ActualizarDepartamentoAction;
use App\Actions\Departamentos\DesactivarDepartamentoAction;
use App\Http\Requests\CreateDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class DepartmentManagementController extends Controller {
    public function __construct() {
        //
    }

    public function index(Request $request): View {
        // Verificar que el usuario tenga el rol requerido
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        $query = Department::query();

        // Filtros
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $departamentos = $query->orderBy('name')->paginate(15);

        return view('pages.admin.departments.index', compact('departamentos'));
    }

    public function create(): View {
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        return view('pages.admin.departments.create');
    }

    public function store(CreateDepartmentRequest $request, CrearDepartamentoAction $action): RedirectResponse {
        $action->handle($request->validated());

        return redirect()->route('admin.departments.index')
            ->with('success', 'Departamento creado exitosamente.');
    }

    public function show(int $departmentId): View {
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        $departamento = Department::with(['empleados'])->findOrFail($departmentId);

        return view('pages.admin.departments.show', compact('departamento'));
    }

    public function edit(int $departmentId): View {
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        $departamento = Department::findOrFail($departmentId);

        return view('pages.admin.departments.edit', compact('departamento'));
    }

    public function update(UpdateDepartmentRequest $request, int $departmentId, ActualizarDepartamentoAction $action): RedirectResponse {
        $action->handle($departmentId, $request->validated());

        return redirect()->route('admin.departments.index')
            ->with('success', 'Departamento actualizado exitosamente.');
    }

    public function destroy(int $departmentId, DesactivarDepartamentoAction $action): RedirectResponse {
        try {
            $action->handle($departmentId);
            return redirect()->route('admin.departments.index')
                ->with('success', 'Departamento desactivado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function restore(int $departmentId, DesactivarDepartamentoAction $action): RedirectResponse {
        try {
            $action->handleRestore($departmentId);
            return redirect()->route('admin.departments.index')
                ->with('success', 'Departamento reactivado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
