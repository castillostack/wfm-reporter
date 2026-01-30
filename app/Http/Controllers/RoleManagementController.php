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

      $usuariosAsignados = $role->users()->orderBy('name')->get();
      $usuariosDisponibles = \App\Models\User::whereDoesntHave('roles', function ($query) use ($role) {
         $query->where('id', $role->id);
      })->orderBy('name')->get();

      return view('pages.admin.roles.edit', compact('role', 'usuariosAsignados', 'usuariosDisponibles'));
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
}
