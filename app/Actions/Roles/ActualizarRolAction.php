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

         return $role->fresh();
      });
   }
}
