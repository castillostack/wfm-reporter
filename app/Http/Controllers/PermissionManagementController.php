<?php

namespace App\Http\Controllers;

use App\Actions\Permisos\CrearPermisoAction;
use App\Actions\Permisos\ActualizarPermisoAction;
use App\Actions\Permisos\EliminarPermisoAction;
use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class PermissionManagementController extends Controller {
   public function __construct() {
      // Verificar que el usuario tenga el rol requerido
      // Solo analistas WFM pueden gestionar permisos
   }

   public function index(Request $request): View {
      // Verificar que el usuario tenga el rol requerido
      if (!auth()->user()->hasRole('analista-wfm')) {
         abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
      }

      $busqueda = $request->get('search');

      $permissions = Permission::withCount('roles')
         ->when($busqueda, function ($query) use ($busqueda) {
            $query->where('name', 'like', "%{$busqueda}%");
         })
         ->orderBy('name')
         ->paginate(15);

      return view('pages.admin.permissions.index', compact('permissions', 'busqueda'));
   }

   public function create(): View {
      // Verificar que el usuario tenga el rol requerido
      if (!auth()->user()->hasRole('analista-wfm')) {
         abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
      }

      return view('pages.admin.permissions.create');
   }

   public function store(CreatePermissionRequest $request, CrearPermisoAction $accion): RedirectResponse {
      // Verificar que el usuario tenga el rol requerido
      if (!auth()->user()->hasRole('analista-wfm')) {
         abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
      }

      try {
         $permiso = $accion->handle($request->validated());

         return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permiso creado correctamente.');
      } catch (\Exception $e) {
         return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Error al crear el permiso: ' . $e->getMessage());
      }
   }

   public function show(Permission $permission): View {
      // Verificar que el usuario tenga el rol requerido
      if (!auth()->user()->hasRole('analista-wfm')) {
         abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
      }

      $permission->load(['roles']);

      return view('pages.admin.permissions.show', compact('permission'));
   }

   public function edit(Permission $permission): View {
      // Verificar que el usuario tenga el rol requerido
      if (!auth()->user()->hasRole('analista-wfm')) {
         abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
      }

      return view('pages.admin.permissions.edit', compact('permission'));
   }

   public function update(UpdatePermissionRequest $request, Permission $permission, ActualizarPermisoAction $accion): RedirectResponse {
      // Verificar que el usuario tenga el rol requerido
      if (!auth()->user()->hasRole('analista-wfm')) {
         abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
      }

      try {
         $accion->handle($permission, $request->validated());

         return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permiso actualizado correctamente.');
      } catch (\Exception $e) {
         return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Error al actualizar el permiso: ' . $e->getMessage());
      }
   }

   public function destroy(Permission $permission, EliminarPermisoAction $accion): RedirectResponse {
      // Verificar que el usuario tenga el rol requerido
      if (!auth()->user()->hasRole('analista-wfm')) {
         abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
      }

      try {
         $accion->handle($permission);

         return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permiso eliminado correctamente.');
      } catch (\Exception $e) {
         return redirect()
            ->back()
            ->with('error', 'Error al eliminar el permiso: ' . $e->getMessage());
      }
   }
}
