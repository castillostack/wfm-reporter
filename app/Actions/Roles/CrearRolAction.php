<?php

declare(strict_types=1);

namespace App\Actions\Roles;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

final class CrearRolAction {
   public function handle(array $datos): Role {
      return DB::transaction(function () use ($datos) {
         return Role::create([
            'name' => $datos['name'],
            'description' => $datos['description'] ?? null,
            'guard_name' => $datos['guard_name'] ?? 'web',
         ]);
      });
   }
}
