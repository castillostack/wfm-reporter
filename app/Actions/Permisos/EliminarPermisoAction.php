<?php

declare(strict_types=1);

namespace App\Actions\Permisos;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

final class EliminarPermisoAction {
   public function handle(Permission $permission): bool {
      return DB::transaction(function () use ($permission) {
         // Verificar que no haya roles usando este permiso
         if ($permission->roles()->count() > 0) {
            throw new \Exception('No se puede eliminar el permiso porque está asignado a uno o más roles.');
         }

         return $permission->delete();
      });
   }
}
