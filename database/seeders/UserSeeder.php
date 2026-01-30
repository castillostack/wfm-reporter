<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder {
    public function run(): void {
        // Crear roles
        $roles = ['analista-wfm', 'director-nacional', 'jefe-departamento', 'coordinador', 'operador'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Asignar permisos a roles
        $this->assignPermissionsToRoles();

        // Leer CSV de empleados
        $csvPath = base_path('docs/matriz_data.csv');
        if (!file_exists($csvPath)) {
            return;
        }

        $data = array_map('str_getcsv', file($csvPath));
        array_shift($data); // Remover header

        foreach ($data as $row) {
            // if (count($row) < 30)
            //     continue; // Validar fila

            $email = trim($row[16]); // CORREO
            $numeroEmpleado = trim($row[34]); // NÚMERO DE EMPLEADO
            $cargo = trim($row[3]); // CARGO SEGÚN FUNCIONES
            $funcionario = trim($row[5]); // FUNCIONARIO

            // if (empty($email) || empty($numeroEmpleado) || $numeroEmpleado === 'NO')
            //     continue;

            // Mapear rol basado en cargo
            $role = $this->mapRole($cargo);

            // Crear usuario si no existe
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $funcionario,
                    'password' => Hash::make('password123'), // Contraseña temporal
                    'employee_code' => $numeroEmpleado === 'NO' ? null : $numeroEmpleado,
                    'id_number' => trim($row[12]), // CÉDULA
                    'phone' => trim($row[23]), // TELÉFONO
                    'gender' => trim($row[33]) === 'F' ? 'F' : 'M', // SEXO
                    'date_of_birth' => $this->parseDate($row[8]), // FECHA NAC
                    'hire_date' => $this->parseDate($row[13]), // FECHA - INICIO DE LABORES
                    'position' => $cargo,
                    'salary' => $this->parseSalary($row[17]), // SALARIO
                    'is_active' => true,
                ]
            );

            // Asignar rol
            if (!$user->hasRole($role)) {
                $user->assignRole($role);
            }
        }

        // Crear usuario analista WFM adicional
        $analyst = User::firstOrCreate(
            ['email' => 'analista@wfm.com'],
            [
                'name' => 'Analista WFM',
                'password' => Hash::make('password123'),
                'employee_code' => 'ANALISTA001',
                'is_active' => true,
            ]
        );
        $analyst->assignRole('analista-wfm');
    }

    private function mapRole(string $cargo): string {
        if (str_contains($cargo, 'Dir.'))
            return 'director-nacional';
        if (str_contains($cargo, 'Jefe'))
            return 'jefe-departamento';
        if (str_contains($cargo, 'Coord.'))
            return 'coordinador';
        if (str_contains($cargo, 'Operador'))
            return 'operador';
        return 'operador'; // Default
    }

    private function parseDate(string $dateStr): ?string {
        if (empty($dateStr))
            return null;
        try {
            return \Carbon\Carbon::createFromFormat('d/m/Y', $dateStr)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parseSalary(string $salaryStr): ?float {
        if (empty($salaryStr))
            return null;
        // Remover caracteres no numéricos excepto punto y coma
        $cleaned = preg_replace('/[^\d.,]/', '', $salaryStr);
        // Convertir a float
        return (float) str_replace(',', '.', $cleaned);
    }

    private function assignPermissionsToRoles(): void {
        // Permisos para analista-wfm (acceso completo)
        $analystRole = Role::where('name', 'analista-wfm')->first();
        if ($analystRole) {
            $analystRole->syncPermissions([
                'users.view', 'users.create', 'users.edit', 'users.delete', 'users.import', 'users.export',
                'employees.view', 'employees.create', 'employees.edit', 'employees.delete', 'employees.import', 'employees.export',
                'departments.view', 'departments.create', 'departments.edit', 'departments.delete',
                'teams.view', 'teams.create', 'teams.edit', 'teams.delete',
                'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
                'permissions.view', 'permissions.create', 'permissions.edit', 'permissions.delete',
                'dashboard.view', 'reports.view', 'reports.generate',
                'profile.view', 'profile.edit',
                'view_own_attendance', 'view_team_attendance', 'view_all_attendance', 'manage_attendance',
            ]);
        }

        // Permisos para director-nacional
        $directorRole = Role::where('name', 'director-nacional')->first();
        if ($directorRole) {
            $directorRole->syncPermissions([
                'users.view', 'employees.view', 'departments.view', 'teams.view',
                'dashboard.view', 'reports.view', 'reports.generate',
                'profile.view', 'profile.edit',
                'view_all_attendance',
            ]);
        }

        // Permisos para jefe-departamento
        $jefeRole = Role::where('name', 'jefe-departamento')->first();
        if ($jefeRole) {
            $jefeRole->syncPermissions([
                'users.view', 'employees.view', 'employees.edit', 'departments.view',
                'teams.view', 'teams.edit', 'dashboard.view', 'reports.view',
                'profile.view', 'profile.edit',
                'view_team_attendance',
            ]);
        }

        // Permisos para coordinador
        $coordinadorRole = Role::where('name', 'coordinador')->first();
        if ($coordinadorRole) {
            $coordinadorRole->syncPermissions([
                'users.view', 'employees.view', 'employees.edit', 'departments.view',
                'teams.view', 'dashboard.view', 'reports.view',
                'profile.view', 'profile.edit',
                'view_team_attendance',
            ]);
        }

        // Permisos para operador (mínimos)
        $operadorRole = Role::where('name', 'operador')->first();
        if ($operadorRole) {
            $operadorRole->syncPermissions([
                'dashboard.view', 'profile.view', 'profile.edit',
                'view_own_attendance',
            ]);
        }
    }
}
