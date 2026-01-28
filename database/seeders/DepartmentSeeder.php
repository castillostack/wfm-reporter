<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder {
   public function run(): void {
      $departments = [
         'DNASA',
         'Jefe DNASA',
         'Voz DNASA',
         'Redes Sociales DNASA',
         'Ingeniería DNASA',
         'Supervisores DNASA',
         'Control y Monitoreo',
         'Coord. De Asist. Serv. Aseg.',
         'Administración DNASA',
         'Dirección Nacional de Asistencia de Servicios de Atención',
      ];

      foreach ($departments as $name) {
         Department::firstOrCreate(['name' => $name]);
      }
   }
}
