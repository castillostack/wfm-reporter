<?php

declare(strict_types=1);

namespace App\Actions\Usuarios;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class ActualizarUsuarioAction {
   public function handle(int $userId, array $datos): User {
      return DB::transaction(function () use ($userId, $datos) {
         $usuario = User::findOrFail($userId);

         // Actualizar campos del usuario
         $camposUsuario = array_intersect_key($datos, array_flip(['name', 'email']));
         if (!empty($camposUsuario)) {
            $usuario->update($camposUsuario);
         }

         // Cambiar password si se proporciona
         if (isset($datos['password']) && !empty($datos['password'])) {
            $usuario->update(['password' => Hash::make($datos['password'])]);
         }

         // Actualizar rol
         if (isset($datos['role'])) {
            $usuario->syncRoles([$datos['role']]);
         }

         // Actualizar o crear empleado
         $camposEmpleado = array_intersect_key($datos, array_flip([
            'first_name', 'last_name', 'employee_number', 'cedula', 'gender',
            'birth_date', 'phone', 'hire_date', 'position', 'department_id', 'supervisor_id'
         ]));

         if (!empty($camposEmpleado)) {
            if ($usuario->empleado) {
               $usuario->empleado->update($camposEmpleado);
            } else {
               $camposEmpleado['user_id'] = $usuario->id;
               Employee::create($camposEmpleado);
            }
         }

         return $usuario->load(['empleado.departamento', 'empleado.supervisor', 'roles']);
      });
   }
}
