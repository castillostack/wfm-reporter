<?php

declare(strict_types=1);

namespace App\Actions\Usuarios;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class CrearUsuarioAction {
   public function handle(array $datos): User {
      return DB::transaction(function () use ($datos) {
         return User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
         ]);
      });
   }
}
