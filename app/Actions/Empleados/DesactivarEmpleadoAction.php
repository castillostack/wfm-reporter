<?php

declare(strict_types=1);

namespace App\Actions\Empleados;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;

final class DesactivarEmpleadoAction {
   public function handle(int $employeeId): void {
      DB::transaction(function () use ($employeeId) {
         $empleado = Employee::findOrFail($employeeId);

         // Si el empleado tiene un usuario asociado, también lo desactivamos
         if ($empleado->usuario) {
            $empleado->usuario->delete();
         }

         $empleado->delete();
      });
   }
}
