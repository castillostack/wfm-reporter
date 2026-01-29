<?php

declare(strict_types=1);

namespace App\Actions\Usuarios;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

final class DesactivarUsuarioAction {
   public function handle(int $userId): bool {
      try {
         return DB::transaction(function () use ($userId) {
            $usuario = User::findOrFail($userId);

            // Verificar si ya está desactivado
            if ($usuario->trashed()) {
               throw new Exception('El usuario ya está desactivado.');
            }

            return $usuario->delete(); // Soft delete
         });
      } catch (Exception $e) {
         throw new Exception('Error al desactivar usuario: ' . $e->getMessage());
      }
   }

   public function handleRestore(int $userId): bool {
      try {
         $usuario = User::withTrashed()->findOrFail($userId);

         // Verificar si ya está activo
         if (!$usuario->trashed()) {
            throw new Exception('El usuario ya está activo.');
         }

         return $usuario->restore();
      } catch (Exception $e) {
         throw new Exception('Error al restaurar usuario: ' . $e->getMessage());
      }
   }

   public function forceDelete(int $userId): bool {
      try {
         $usuario = User::withTrashed()->findOrFail($userId);
         return $usuario->forceDelete();
      } catch (Exception $e) {
         throw new Exception('Error al eliminar permanentemente usuario: ' . $e->getMessage());
      }
   }
}
