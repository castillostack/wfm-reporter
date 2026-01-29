<?php

declare(strict_types=1);

namespace App\Actions\Usuarios;

use App\Models\User;
use Illuminate\Support\Facades\DB;

final class DesactivarUsuarioAction {
   public function handle(int $userId): bool {
      return DB::transaction(function () use ($userId) {
         $usuario = User::findOrFail($userId);
         return $usuario->delete(); // Soft delete
      });
   }

   public function handleRestore(int $userId): bool {
      $usuario = User::withTrashed()->findOrFail($userId);
      return $usuario->restore();
   }

   public function forceDelete(int $userId): bool {
      $usuario = User::withTrashed()->findOrFail($userId);
      return $usuario->forceDelete();
   }
}
