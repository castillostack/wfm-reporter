<?php

declare(strict_types=1);

namespace App\Actions\Departamentos;

use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Exception;

final class CrearDepartamentoAction {
   public function handle(array $datos): Department {
      try {
         return DB::transaction(function () use ($datos) {
            return Department::create([
               'name' => $datos['name'],
            ]);
         });
      } catch (Exception $e) {
         throw new Exception('Error al crear departamento: ' . $e->getMessage());
      }
   }
}
