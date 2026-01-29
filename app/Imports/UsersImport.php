<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToCollection, WithHeadingRow, WithValidation {
    public function collection(Collection $rows) {
        foreach ($rows as $row) {
            // Crear usuario
            $usuario = User::create([
                'name' => $row['nombre_completo'],
                'email' => $row['email'],
                'password' => Hash::make($row['password'] ?? Str::random(12)),
            ]);

            // Asignar rol
            if (isset($row['rol'])) {
                $usuario->assignRole($row['rol']);
            }

            // Crear empleado
            $department = Department::where('name', $row['departamento'])->first();

            Employee::create([
                'user_id' => $usuario->id,
                'first_name' => $row['nombre'] ?? '',
                'last_name' => $row['apellido'] ?? '',
                'employee_number' => $row['numero_empleado'],
                'cedula' => $row['cedula'],
                'gender' => $row['genero'] ?? null,
                'birth_date' => $row['fecha_nacimiento'] ?? null,
                'phone' => $row['telefono'] ?? null,
                'hire_date' => $row['fecha_contratacion'],
                'position' => $row['cargo'] ?? null,
                'department_id' => $department?->id,
            ]);
        }
    }

    public function rules(): array {
        return [
            'nombre_completo' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'numero_empleado' => 'required|string|unique:employees,employee_number',
            'cedula' => 'required|string|unique:employees,cedula',
            'fecha_contratacion' => 'required|date',
            'departamento' => 'required|string|exists:departments,name',
            'rol' => 'required|string|in:Director,Jefe,Coordinador,Operador',
        ];
    }

    public function customValidationMessages() {
        return [
            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.unique' => 'Este email ya está registrado.',
            'numero_empleado.required' => 'El número de empleado es obligatorio.',
            'numero_empleado.unique' => 'Este número de empleado ya existe.',
            'cedula.required' => 'La cédula es obligatoria.',
            'cedula.unique' => 'Esta cédula ya está registrada.',
            'fecha_contratacion.required' => 'La fecha de contratación es obligatoria.',
            'departamento.required' => 'El departamento es obligatorio.',
            'departamento.exists' => 'El departamento no existe.',
            'rol.required' => 'El rol es obligatorio.',
            'rol.in' => 'El rol debe ser: Director, Jefe, Coordinador u Operador.',
        ];
    }
}
