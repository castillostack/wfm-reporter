<?php

declare(strict_types=1);

namespace App\Actions\Equipos;

use App\Models\Team;
use Illuminate\Support\Facades\DB;

final class ActualizarEquipoAction {
   public function handle(Team $equipo, array $datos): Team {
      return DB::transaction(function () use ($equipo, $datos) {
         $equipo->update([
            'name' => $datos['name'],
            'description' => $datos['description'] ?? null,
            'leader_id' => $datos['leader_id'] ?? null,
            'department_id' => $datos['department_id'] ?? null,
         ]);

         return $equipo->fresh();
      });
   }
}
