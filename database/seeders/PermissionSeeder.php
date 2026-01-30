<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder {
   public function run(): void {
      $permissions = [
         // Usuarios
         'users.view',
         'users.create',
         'users.edit',
         'users.delete',
         'users.import',
         'users.export',

         // Empleados
         'employees.view',
         'employees.create',
         'employees.edit',
         'employees.delete',
         'employees.import',
         'employees.export',

         // Departamentos
         'departments.view',
         'departments.create',
         'departments.edit',
         'departments.delete',

         // Equipos
         'teams.view',
         'teams.create',
         'teams.edit',
         'teams.delete',

         // Roles y Permisos
         'roles.view',
         'roles.create',
         'roles.edit',
         'roles.delete',
         'permissions.view',
         'permissions.create',
         'permissions.edit',
         'permissions.delete',

         // Dashboard y reportes
         'dashboard.view',
         'reports.view',
         'reports.generate',

         // Perfil
         'profile.view',
         'profile.edit',
      ];

      foreach ($permissions as $permission) {
         Permission::firstOrCreate(['name' => $permission]);
      }
   }
}
