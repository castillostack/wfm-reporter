<?php

declare(strict_types=1);

namespace App\Actions\Equipos;

use App\Models\Team;
use Illuminate\Support\Facades\DB;

final class CrearEquipoAction {
   public function handle(array $datos): Team {
      return DB::transaction(function () use ($datos) {
         return Team::create([
            'name' => $datos['name'],
            'description' => $datos['description'] ?? null,
            'leader_id' => $datos['leader_id'] ?? null,
            'department_id' => $datos['department_id'] ?? null,
         ]);
      });
   }
}
