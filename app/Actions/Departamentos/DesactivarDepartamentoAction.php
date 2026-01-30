<?php

declare(strict_types=1);

namespace App\Actions\Departamentos;

use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Exception;

final class DesactivarDepartamentoAction {
   public function handle(int $departmentId): bool {
      try {
         return DB::transaction(function () use ($departmentId) {
            $departamento = Department::findOrFail($departmentId);

            // Verificar si ya está desactivado
            if ($departamento->trashed()) {
               throw new Exception('El departamento ya está desactivado.');
            }

            // Verificar si tiene empleados activos
            if ($departamento->empleados()->whereNull('deleted_at')->exists()) {
               throw new Exception('No se puede desactivar el departamento porque tiene empleados activos asignados.');
            }

            return $departamento->delete(); // Soft delete
         });
      } catch (Exception $e) {
         throw new Exception('Error al desactivar departamento: ' . $e->getMessage());
      }
   }

   public function handleRestore(int $departmentId): bool {
      try {
         $departamento = Department::withTrashed()->findOrFail($departmentId);

         // Verificar si ya está activo
         if (!$departamento->trashed()) {
            throw new Exception('El departamento ya está activo.');
         }

         return $departamento->restore();
      } catch (Exception $e) {
         throw new Exception('Error al restaurar departamento: ' . $e->getMessage());
      }
   }
}
