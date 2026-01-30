<?php

declare(strict_types=1);

namespace App\Actions\Equipos;

use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class DesactivarEquipoAction {
   public function handle(Team $equipo): void {
      DB::transaction(function () use ($equipo) {
         // Verificar que no haya empleados activos en el equipo
         if ($equipo->empleados()->whereNull('deleted_at')->exists()) {
            throw ValidationException::withMessages([
               'equipo' => 'No se puede desactivar el equipo porque tiene empleados activos asignados.',
            ]);
         }

         $equipo->delete();
      });
   }
}
