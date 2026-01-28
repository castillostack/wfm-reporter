<?php

declare(strict_types=1);

namespace App\Actions\Usuarios;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class ActualizarPerfilUsuarioAction
{
    public function handle(int $userId, array $datos): User
    {
        return DB::transaction(function () use ($userId, $datos) {
            $usuario = User::findOrFail($userId);

            // Actualizar campos del usuario
            $camposUsuario = array_intersect_key($datos, array_flip(['name', 'email']));
            if (!empty($camposUsuario)) {
                $usuario->update($camposUsuario);
            }

            // Actualizar campos del empleado si existe
            if ($usuario->empleado) {
                $camposEmpleado = array_intersect_key($datos, array_flip(['first_name', 'last_name', 'phone', 'position']));
                if (!empty($camposEmpleado)) {
                    $usuario->empleado->update($camposEmpleado);
                }
            }

            return $usuario->load(['empleado.departamento', 'empleado.supervisor']);
        });
    }
}