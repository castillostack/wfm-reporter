<?php

declare(strict_types=1);

namespace App\Actions\Usuarios;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final class CambiarContrasenaUsuarioAction {
   public function handle(int $userId, string $currentPassword, string $newPassword): void {
      $usuario = User::findOrFail($userId);

      // Verificar que la contraseña actual sea correcta
      if (!Hash::check($currentPassword, $usuario->password)) {
         throw ValidationException::withMessages([
            'current_password' => 'La contraseña actual es incorrecta.'
         ]);
      }

      // Actualizar la contraseña
      $usuario->update([
         'password' => Hash::make($newPassword)
      ]);
   }
}