<?php

declare(strict_types=1);

namespace App\Actions\Usuarios;

use Illuminate\Support\Facades\Auth;

final class LogoutUsuarioAction {
   public function handle(): void {
      Auth::logout();
   }
}
