<?php

declare(strict_types=1);

namespace App\Actions\Usuarios;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class CrearUsuarioAction {
   public function handle(array $datos): User {
      return DB::transaction(function () use ($datos) {
         // Crear usuario
         $usuario = User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password'] ?? Str::random(12)), // Generar password si no se proporciona
         ]);

         // Asignar rol
         if (isset($datos['role'])) {
            $usuario->assignRole($datos['role']);
         }

         // Crear empleado si se proporcionan datos
         if (isset($datos['first_name']) || isset($datos['employee_number'])) {
            Employee::create([
               'user_id' => $usuario->id,
               'first_name' => $datos['first_name'] ?? '',
               'last_name' => $datos['last_name'] ?? '',
               'employee_number' => $datos['employee_number'] ?? null,
               'cedula' => $datos['cedula'] ?? null,
               'gender' => $datos['gender'] ?? null,
               'birth_date' => $datos['birth_date'] ?? null,
               'phone' => $datos['phone'] ?? null,
               'hire_date' => $datos['hire_date'] ?? null,
               'position' => $datos['position'] ?? null,
               'department_id' => $datos['department_id'] ?? null,
               'supervisor_id' => $datos['supervisor_id'] ?? null,
            ]);
         }

         return $usuario->load(['empleado.departamento', 'roles']);
      });
   }
}
