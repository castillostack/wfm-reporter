<?php

namespace App\Exports;

use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping {
   private array $filters;

   public function __construct(array $filters = []) {
      $this->filters = $filters;
   }

   public function collection(): Collection {
      $query = Employee::with(['departamento', 'supervisor', 'usuario']);

      // Aplicar filtros
      if (!empty($this->filters['search'])) {
         $query->where(function ($q) {
            $q->where('first_name', 'like', '%' . $this->filters['search'] . '%')
               ->orWhere('last_name', 'like', '%' . $this->filters['search'] . '%')
               ->orWhere('employee_number', 'like', '%' . $this->filters['search'] . '%')
               ->orWhere('email', 'like', '%' . $this->filters['search'] . '%');
         });
      }

      if (!empty($this->filters['department'])) {
         $query->where('department_id', $this->filters['department']);
      }

      if (!empty($this->filters['position'])) {
         $query->where('position', 'like', '%' . $this->filters['position'] . '%');
      }

      if (!empty($this->filters['status'])) {
         if ($this->filters['status'] === 'active') {
            $query->whereNull('deleted_at');
         } elseif ($this->filters['status'] === 'inactive') {
            $query->onlyTrashed();
         }
      }

      return $query->get();
   }

   public function headings(): array {
      return [
         'Número de Empleado',
         'Nombre',
         'Apellido',
         'Email',
         'Teléfono',
         'Cargo',
         'Departamento',
         'Supervisor',
         'Fecha de Contratación',
         'Salario',
         'Dirección',
         'Contacto de Emergencia',
         'Teléfono de Emergencia',
         'Estado',
      ];
   }

   public function map($employee): array {
      return [
         $employee->employee_number,
         $employee->first_name,
         $employee->last_name,
         $employee->email,
         $employee->phone,
         $employee->position,
         $employee->departamento?->name ?? 'Sin asignar',
         $employee->supervisor ? $employee->supervisor->first_name . ' ' . $employee->supervisor->last_name : 'Sin supervisor',
         $employee->hire_date?->format('d/m/Y'),
         $employee->salary,
         $employee->address,
         $employee->emergency_contact_name,
         $employee->emergency_contact_phone,
         $employee->deleted_at ? 'Inactivo' : 'Activo',
      ];
   }
}
