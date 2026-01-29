<?php

namespace App\Imports;

use App\Actions\Empleados\CrearEmpleadoAction;
use App\Models\Department;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeesImport implements ToCollection, WithHeadingRow, WithValidation {
   private CrearEmpleadoAction $action;
   private array $errors = [];
   private int $processed = 0;

   public function __construct() {
      $this->action = new CrearEmpleadoAction();
   }

   public function collection(Collection $rows) {
      foreach ($rows as $row) {
         try {
            // Mapear datos del Excel
            $datos = [
               'employee_number' => $row['numero_empleado'] ?? $row['employee_number'],
               'first_name' => $row['nombre'] ?? $row['first_name'],
               'last_name' => $row['apellido'] ?? $row['last_name'],
               'email' => $row['email'] ?? $row['correo'],
               'phone' => $row['telefono'] ?? $row['phone'],
               'position' => $row['cargo'] ?? $row['position'],
               'department_id' => $this->getDepartmentId($row['departamento'] ?? $row['department']),
               'hire_date' => $row['fecha_contratacion'] ?? $row['hire_date'],
               'salary' => $row['salario'] ?? $row['salary'],
               'address' => $row['direccion'] ?? $row['address'],
            ];

            // Validar y crear empleado
            $this->action->handle(array_filter($datos));
            $this->processed++;

         } catch (\Exception $e) {
            $this->errors[] = "Fila " . ($this->processed + count($this->errors) + 2) . ": " . $e->getMessage();
         }
      }
   }

   private function getDepartmentId(?string $departmentName): ?int {
      if (!$departmentName) {
         return null;
      }

      $department = Department::where('name', 'like', '%' . $departmentName . '%')->first();
      return $department ? $department->id : null;
   }

   public function rules(): array {
      return [
         'numero_empleado' => 'required',
         'employee_number' => 'required',
         'nombre' => 'required',
         'first_name' => 'required',
         'apellido' => 'required',
         'last_name' => 'required',
         'email' => 'required|email',
         'correo' => 'required|email',
      ];
   }

   public function getErrors(): array {
      return $this->errors;
   }

   public function getProcessedCount(): int {
      return $this->processed;
   }
}
