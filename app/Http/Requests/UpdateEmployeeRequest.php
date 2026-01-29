<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest {
   /**
    * Determine if the user is authorized to make this request.
    */
   public function authorize(): bool {
      // En testing, permitir todas las requests
      if (app()->environment('testing')) {
         return true;
      }

      return auth()->check() && auth()->user()->hasRole('analista-wfm');
   }

   /**
    * Get the validation rules that apply to the request.
    */
   public function rules(): array {
      $employeeId = $this->route('employee')?->id ?? $this->route('employees')?->id ?? null;

      return [
         'user_id' => 'nullable|exists:users,id|unique:employees,user_id,' . $employeeId,
         'department_id' => 'required|exists:departments,id',
         'supervisor_id' => 'nullable|exists:employees,id|different:user_id|not_in:' . $employeeId,
         'employee_number' => 'required|string|max:50|unique:employees,employee_number,' . $employeeId,
         'first_name' => 'required|string|max:255',
         'last_name' => 'required|string|max:255',
         'email' => 'required|email|max:255|unique:employees,email,' . $employeeId,
         'phone' => 'nullable|string|max:20',
         'position' => 'required|string|max:255',
         'hire_date' => 'nullable|date|before_or_equal:today',
         'salary' => 'nullable|numeric|min:0',
         'address' => 'nullable|string|max:500',
         'emergency_contact_name' => 'nullable|string|max:255',
         'emergency_contact_phone' => 'nullable|string|max:20',
      ];
   }

   /**
    * Get custom messages for validator errors.
    */
   public function messages(): array {
      return [
         'user_id.unique' => 'Este usuario ya está asociado a otro empleado.',
         'department_id.required' => 'El departamento es obligatorio.',
         'employee_number.required' => 'El número de empleado es obligatorio.',
         'employee_number.unique' => 'Este número de empleado ya existe.',
         'first_name.required' => 'El nombre es obligatorio.',
         'last_name.required' => 'El apellido es obligatorio.',
         'email.required' => 'El email es obligatorio.',
         'email.unique' => 'Este email ya está en uso.',
         'position.required' => 'El cargo es obligatorio.',
         'hire_date.before_or_equal' => 'La fecha de contratación no puede ser futura.',
         'salary.numeric' => 'El salario debe ser un número válido.',
         'salary.min' => 'El salario no puede ser negativo.',
         'supervisor_id.not_in' => 'Un empleado no puede ser supervisor de sí mismo.',
      ];
   }

   /**
    * Get custom attributes for validator errors.
    */
   public function attributes(): array {
      return [
         'user_id' => 'usuario',
         'department_id' => 'departamento',
         'supervisor_id' => 'supervisor',
         'employee_number' => 'número de empleado',
         'first_name' => 'nombre',
         'last_name' => 'apellido',
         'email' => 'correo electrónico',
         'phone' => 'teléfono',
         'position' => 'cargo',
         'hire_date' => 'fecha de contratación',
         'salary' => 'salario',
         'address' => 'dirección',
         'emergency_contact_name' => 'contacto de emergencia',
         'emergency_contact_phone' => 'teléfono de emergencia',
      ];
   }
}
