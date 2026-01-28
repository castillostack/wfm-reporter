<?php

declare(strict_types=1);

namespace App\Actions\Usuarios;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class AutenticarUsuarioAction {
   public function handle(array $datos): ?User {
      if (Auth::attempt(['email' => $datos['email'], 'password' => $datos['password']], $datos['remember'] ?? false)) {
         return Auth::user();
      }

      return null;
   }
}
