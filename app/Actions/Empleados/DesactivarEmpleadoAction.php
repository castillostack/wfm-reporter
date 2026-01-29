<?php

declare(strict_types=1);

namespace App\Actions\Empleados;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Exception;

final class DesactivarEmpleadoAction {
   public function handle(int $employeeId): void {
      try {
         DB::transaction(function () use ($employeeId) {
            $empleado = Employee::findOrFail($employeeId);

            // Verificar si ya está desactivado
            if ($empleado->trashed()) {
               throw new Exception('El empleado ya está desactivado.');
            }

            // Si el empleado tiene un usuario asociado, también lo desactivamos
            if ($empleado->usuario) {
               $empleado->usuario->delete();
            }

            $empleado->delete();
         });
      } catch (Exception $e) {
         throw new Exception('Error al desactivar empleado: ' . $e->getMessage());
      }
   }
}
