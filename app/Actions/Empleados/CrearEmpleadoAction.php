<?php

declare(strict_types=1);

namespace App\Actions\Empleados;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;

final class CrearEmpleadoAction {
   public function handle(array $datos): Employee {
      return DB::transaction(function () use ($datos) {
         return Employee::create([
            'user_id' => $datos['user_id'] ?? null,
            'department_id' => $datos['department_id'],
            'supervisor_id' => $datos['supervisor_id'] ?? null,
            'employee_number' => $datos['employee_number'],
            'first_name' => $datos['first_name'],
            'last_name' => $datos['last_name'],
            'email' => $datos['email'],
            'phone' => $datos['phone'] ?? null,
            'position' => $datos['position'],
            'hire_date' => $datos['hire_date'] ?? now(),
            'salary' => $datos['salary'] ?? null,
            'address' => $datos['address'] ?? null,
            'emergency_contact_name' => $datos['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $datos['emergency_contact_phone'] ?? null,
         ]);
      });
   }
}
