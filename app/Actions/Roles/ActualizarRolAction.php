<?php

declare(strict_types=1);

namespace App\Actions\Roles;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

final class ActualizarRolAction {
   public function handle(Role $role, array $datos): Role {
      return DB::transaction(function () use ($role, $datos) {
         $role->update([
            'name' => $datos['name'],
            'description' => $datos['description'] ?? null,
         ]);

         // Si se proporcionan permisos, sincronizarlos
         if (isset($datos['permissions'])) {
            $role->syncPermissions($datos['permissions']);
         }

         // Si se proporcionan usuarios asignados, sincronizarlos
         if (isset($datos['assigned_users'])) {
            $userIds = json_decode($datos['assigned_users'], true) ?? [];
            $role->users()->sync($userIds);
         }

         return $role->fresh();
      });
   }
}
