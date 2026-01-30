<?php

declare(strict_types=1);

namespace App\Actions\Permisos;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

final class CrearPermisoAction {
   public function handle(array $datos): Permission {
      return DB::transaction(function () use ($datos) {
         return Permission::create([
            'name' => $datos['name'],
            'guard_name' => $datos['guard_name'] ?? 'web',
         ]);
      });
   }
}
