<?php

declare(strict_types=1);

namespace App\Actions\Roles;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

final class EliminarRolAction {
   public function handle(Role $role): bool {
      return DB::transaction(function () use ($role) {
         // Verificar que no haya usuarios con este rol antes de eliminar
         if ($role->users()->count() > 0) {
            throw new \Exception('No se puede eliminar el rol porque tiene usuarios asignados.');
         }

         return $role->delete();
      });
   }
}
