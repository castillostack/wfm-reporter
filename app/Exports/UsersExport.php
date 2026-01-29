<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping {
    protected $filters;

    public function __construct(array $filters = []) {
        $this->filters = $filters;
    }

    public function collection(): Collection {
        $query = User::with(['empleado.departamento', 'roles']);

        // Aplicar filtros
        if (!empty($this->filters['search'])) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->filters['search'] . '%')
                    ->orWhere('email', 'like', '%' . $this->filters['search'] . '%')
                    ->orWhereHas('empleado', function ($emp) {
                        $emp->where('first_name', 'like', '%' . $this->filters['search'] . '%')
                            ->orWhere('last_name', 'like', '%' . $this->filters['search'] . '%')
                            ->orWhere('employee_number', 'like', '%' . $this->filters['search'] . '%');
                    });
            });
        }

        if (!empty($this->filters['department'])) {
            $query->whereHas('empleado', function ($q) {
                $q->where('department_id', $this->filters['department']);
            });
        }

        if (!empty($this->filters['role'])) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->filters['role']);
            });
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
            'Nombre Completo',
            'Email',
            'Rol',
            'Número Empleado',
            'Cédula',
            'Nombre',
            'Apellido',
            'Género',
            'Fecha Nacimiento',
            'Teléfono',
            'Fecha Contratación',
            'Cargo',
            'Departamento',
            'Estado',
            'Fecha Creación',
        ];
    }

    public function map($user): array {
        return [
            $user->name,
            $user->email,
            $user->roles->first()?->name ?? '',
            $user->empleado?->employee_number ?? '',
            $user->empleado?->cedula ?? '',
            $user->empleado?->first_name ?? '',
            $user->empleado?->last_name ?? '',
            $user->empleado?->gender ?? '',
            $user->empleado?->birth_date?->format('d/m/Y') ?? '',
            $user->empleado?->phone ?? '',
            $user->empleado?->hire_date?->format('d/m/Y') ?? '',
            $user->empleado?->position ?? '',
            $user->empleado?->departamento?->name ?? '',
            $user->trashed() ? 'Inactivo' : 'Activo',
            $user->created_at->format('d/m/Y H:i'),
        ];
    }
}
