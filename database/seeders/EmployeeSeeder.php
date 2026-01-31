<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder {
    public function run(): void {
        // Leer CSV
        $csvPath = base_path('docs/matriz_data.csv');
        if (!file_exists($csvPath)) {
            return;
        }

        $data = array_map('str_getcsv', file($csvPath));
        print_r(count($data) . " filas leídas del CSV de empleados.\n");
        array_shift($data); // Remover header

        foreach ($data as $row) {
            // if (count($row) < 30)
            //     continue;

            $email = trim($row[16]);
            $numeroEmpleado = trim($row[34]);
            $seccion = trim($row[2]); // SECCIÓN / DEPARTAMENTO
            $funcionario = trim($row[5]);
            $cedula = trim($row[12]);
            $telefono = trim($row[23]);
            $provincia = trim($row[24]);
            $distrito = trim($row[25]);
            $direccion = trim($row[27]);
            $sexo = trim($row[33]);

            $user = User::where('email', $email)->first();
            // if (!$user)
            //     continue;

            // Buscar departamento por nombre
            $department = Department::where('name', $seccion)->first();
            if (!$department) {
                // Si no existe, asignar DNASA
                $department = Department::where('name', 'DNASA')->first();
            }

            // Crear empleado si no existe
            Employee::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => explode(', ', $funcionario)[1] ?? $funcionario,
                    'last_name' => explode(', ', $funcionario)[0] ?? '',
                    'employee_number' => $numeroEmpleado,
                    'cedula' => $cedula,
                    'gender' => $sexo === 'F' ? 'F' : 'M',
                    'phone' => $telefono,
                    'position' => trim($row[3]), // CARGO SEGÚN FUNCIONES
                    'department_id' => $department?->id,
                    'salary' => $user->salary, // Ya parseado en UserSeeder
                    'hire_date' => $user->hire_date,
                    'birth_date' => $user->date_of_birth,
                ]
            );
        }
    }
}
