<?php

namespace App\Http\Controllers;

use App\Actions\Usuarios\CrearUsuarioAction;
use App\Actions\Usuarios\ActualizarUsuarioAction;
use App\Actions\Usuarios\DesactivarUsuarioAction;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UsersExport;

class UserManagementController extends Controller {
    public function __construct() {
    }

    public function index(Request $request): View {
        // Verificar que el usuario tenga el rol requerido
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        $query = User::with(['empleado.departamento', 'roles']);

        // Filtros
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhereHas('empleado', function ($emp) use ($request) {
                        $emp->where('first_name', 'like', '%' . $request->search . '%')
                            ->orWhere('last_name', 'like', '%' . $request->search . '%')
                            ->orWhere('employee_number', 'like', '%' . $request->search . '%');
                    });
            });
        }

        if ($request->filled('department')) {
            $query->whereHas('empleado', function ($q) use ($request) {
                $q->where('department_id', $request->department);
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('deleted_at');
            } elseif ($request->status === 'inactive') {
                $query->onlyTrashed();
            }
        }

        $usuarios = $query->paginate(15);
        $departamentos = Department::all();

        return view('pages.admin.users.index', compact('usuarios', 'departamentos'));
    }

    public function create(): View {
        $departamentos = Department::all();
        $usuarios = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['Jefe', 'Coordinador']);
        })->get();

        return view('pages.admin.users.create', compact('departamentos', 'usuarios'));
    }

    public function store(CreateUserRequest $request, CrearUsuarioAction $action): RedirectResponse {
        $usuario = $action->handle($request->validated());

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado exitosamente. Credenciales enviadas por email.');
    }

    public function show(int $userId): View {
        $user = User::with(['empleado.departamento', 'empleado.supervisor', 'roles', 'permissions'])
            ->findOrFail($userId);

        return view('pages.admin.users.show', compact('user'));
    }

    public function edit(int $userId): View {
        $user = User::with(['empleado'])->findOrFail($userId);
        $departamentos = Department::all();
        $usuarios = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['Jefe', 'Coordinador']);
        })->get();

        return view('pages.admin.users.edit', compact('user', 'departamentos', 'usuarios'));
    }

    public function update(UpdateUserRequest $request, int $userId, ActualizarUsuarioAction $action): RedirectResponse {
        $action->handle($userId, $request->validated());

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy($id, DesactivarUsuarioAction $action): RedirectResponse {
        try {
            $action->handle((int) $id);
            return redirect()->route('admin.users.index')
                ->with('success', 'Usuario desactivado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function restore($id, DesactivarUsuarioAction $action): RedirectResponse {
        try {
            $action->handleRestore($id);
            return redirect()->route('admin.users.index')
                ->with('success', 'Usuario reactivado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function import(Request $request): JsonResponse {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048'
        ]);

        try {
            Excel::import(new UsersImport, $request->file('file'));

            return response()->json([
                'success' => true,
                'message' => 'Usuarios importados exitosamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al importar usuarios: ' . $e->getMessage()
            ], 422);
        }
    }

    public function export(Request $request) {
        return Excel::download(new UsersExport($request->all()), 'usuarios_' . now()->format('Y-m-d') . '.xlsx');
    }
}
