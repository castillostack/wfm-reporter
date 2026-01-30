<?php

declare(strict_types=1);

namespace App\Actions\Permisos;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

final class ActualizarPermisoAction {
   public function handle(Permission $permission, array $datos): Permission {
      return DB::transaction(function () use ($permission, $datos) {
         $permission->update([
            'name' => $datos['name'],
            'guard_name' => $datos['guard_name'] ?? $permission->guard_name,
         ]);

         return $permission->fresh();
      });
   }
}
