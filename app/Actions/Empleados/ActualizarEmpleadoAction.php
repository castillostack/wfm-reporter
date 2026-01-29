<?php

declare(strict_types=1);

namespace App\Actions\Empleados;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;

final class ActualizarEmpleadoAction {
   public function handle(int $employeeId, array $datos): Employee {
      return DB::transaction(function () use ($employeeId, $datos) {
         $empleado = Employee::findOrFail($employeeId);

         $camposActualizables = [
            'user_id', 'department_id', 'supervisor_id', 'employee_number',
            'first_name', 'last_name', 'email', 'phone', 'position',
            'hire_date', 'salary', 'address', 'emergency_contact_name',
            'emergency_contact_phone'
         ];

         $datosActualizar = array_intersect_key($datos, array_flip($camposActualizables));

         $empleado->update($datosActualizar);

         return $empleado->load(['departamento', 'supervisor', 'usuario']);
      });
   }
}
