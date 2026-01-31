<?php

namespace App\Http\Controllers;

use App\Actions\Roles\CrearRolAction;
use App\Actions\Roles\ActualizarRolAction;
use App\Actions\Roles\EliminarRolAction;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use App\Models\User; // Asegúrate de importar el modelo User

class RoleManagementController extends Controller {
    public function __construct() {
        // Verificar que el usuario tenga el rol requerido
        // Solo analistas WFM pueden gestionar roles
    }

    public function index(Request $request): View {
        // Verificar que el usuario tenga el rol requerido
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        $busqueda = $request->get('search');

        $roles = Role::withCount('users')
            ->when($busqueda, function ($query) use ($busqueda) {
                $query->where('name', 'like', "%{$busqueda}%")
                    ->orWhere('description', 'like', "%{$busqueda}%");
            })
            ->orderBy('name')
            ->paginate(15);

        return view('pages.admin.roles.index', compact('roles', 'busqueda'));
    }

    public function create(): View {
        // Verificar que el usuario tenga el rol requerido
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        return view('pages.admin.roles.create');
    }

    public function store(CreateRoleRequest $request, CrearRolAction $accion): RedirectResponse {
        // Verificar que el usuario tenga el rol requerido
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        try {
            $rol = $accion->handle($request->validated());

            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Rol creado correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al crear el rol: ' . $e->getMessage());
        }
    }

    public function show(Role $role): View {
        // Verificar que el usuario tenga el rol requerido
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        $role->load(['users', 'permissions']);

        return view('pages.admin.roles.show', compact('role'));
    }

    public function edit(Role $role): View {
        // Verificar que el usuario tenga el rol requerido
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        return view('pages.admin.roles.edit', compact('role'));
    }

    public function update(UpdateRoleRequest $request, Role $role, ActualizarRolAction $accion): RedirectResponse {
        // Verificar que el usuario tenga el rol requerido
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        try {
            $accion->handle($role, $request->validated());

            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Rol actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al actualizar el rol: ' . $e->getMessage());
        }
    }

    public function destroy(Role $role, EliminarRolAction $accion): RedirectResponse {
        // Verificar que el usuario tenga el rol requerido
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        try {
            $accion->handle($role);

            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Rol eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al eliminar el rol: ' . $e->getMessage());
        }
    }

    public function manageUsers(Role $role): View {
        // Verificar que el usuario tenga el rol requerido
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        // Obtener todos los usuarios que no tienen roles asignados
        // $users = User::whereDoesntHave('roles')->get();
        $users = User::with('roles')->orderBy('name')->get();
        $roleUsers = $role->users->pluck('id')->toArray();

        return view('pages.admin.roles.manage-users', compact('role', 'users', 'roleUsers'));
    }

    public function updateUsers(Request $request, Role $role): RedirectResponse {
        // Verificar que el usuario tenga el rol requerido
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        $request->validate([
            'users' => 'nullable|string',
            'action' => 'nullable|string|in:assign_selected,remove_selected,assign_all,remove_all,save'
        ]);

        try {
            // Convertir string a array de IDs
            $incomingUserIds = $request->input('users')
                ? array_map('intval', explode(',', $request->input('users')))
                : [];

            // Obtener IDs actuales que ya tienen el rol
            $currentUserIds = User::role($role->name)->pluck('id')->toArray();

            // Calcular diferencias (diff)
            $usersToAdd = array_diff($incomingUserIds, $currentUserIds);     // Nuevos usuarios para este rol
            $usersToRemove = array_diff($currentUserIds, $incomingUserIds);  // Usuarios a remover de este rol

            // Para UsersToAdd: Aplicar syncRoles (elimina roles anteriores y asigna el nuevo)
            if (!empty($usersToAdd)) {
                User::whereIn('id', $usersToAdd)->get()->each(function ($user) use ($role) {
                    // syncRoles elimina cualquier rol previo y asigna solo el nuevo rol
                    $user->syncRoles([$role->name]);
                });
            }

            // Para UsersToRemove: Remover el rol específico (quedan sin rol)
            if (!empty($usersToRemove)) {
                User::whereIn('id', $usersToRemove)->get()->each(function ($user) use ($role) {
                    $user->removeRole($role->name);
                });
            }

            return redirect()
                ->route('admin.roles.users.manage', $role)
                ->with('success', 'Usuarios del rol actualizados correctamente.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al actualizar usuarios del rol: ' . $e->getMessage());
        }
    }
}
