<?php

declare(strict_types=1);

namespace App\Actions\Departamentos;

use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Exception;

final class ActualizarDepartamentoAction {
   public function handle(int $departmentId, array $datos): Department {
      try {
         return DB::transaction(function () use ($departmentId, $datos) {
            $departamento = Department::findOrFail($departmentId);

            $departamento->update([
               'name' => $datos['name'],
            ]);

            return $departamento->fresh();
         });
      } catch (Exception $e) {
         throw new Exception('Error al actualizar departamento: ' . $e->getMessage());
      }
   }
}
