<?php

declare(strict_types=1);

namespace App\Actions\Usuarios;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class ObtenerPerfilUsuarioAction
{
    public function handle(int $userId): User
    {
        $usuario = User::with(['empleado.departamento', 'empleado.supervisor'])->find($userId);

        if (!$usuario) {
            throw new ModelNotFoundException('Usuario no encontrado');
        }

        return $usuario;
    }
}