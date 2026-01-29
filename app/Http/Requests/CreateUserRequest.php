<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest {
   /**
    * Determine if the user is authorized to make this request.
    */
   public function authorize(): bool {
      return auth()->check() && auth()->user()->hasRole('Analista WFM');
   }

   /**
    * Get the validation rules that apply to the request.
    *
    * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
    */
   public function rules(): array {
      return [
         'name' => 'required|string|max:255',
         'email' => 'required|email|unique:users,email',
         'password' => 'nullable|string|min:8|confirmed',
         'role' => 'required|string|in:Director,Jefe,Coordinador,Operador',
         'first_name' => 'required|string|max:255',
         'last_name' => 'required|string|max:255',
         'employee_number' => 'required|string|unique:employees,employee_number',
         'cedula' => 'required|string|unique:employees,cedula',
         'gender' => 'nullable|string|in:Masculino,Femenino,Otro',
         'birth_date' => 'nullable|date|before:today',
         'phone' => 'nullable|string|max:20',
         'hire_date' => 'required|date',
         'position' => 'nullable|string|max:255',
         'department_id' => 'required|exists:departments,id',
         'supervisor_id' => 'nullable|exists:users,id',
      ];
   }

   /**
    * Get custom messages for validator errors.
    */
   public function messages(): array {
      return [
         'name.required' => 'El nombre completo es obligatorio.',
         'email.required' => 'El email es obligatorio.',
         'email.unique' => 'Este email ya está registrado.',
         'role.required' => 'Debe seleccionar un rol.',
         'first_name.required' => 'El nombre es obligatorio.',
         'last_name.required' => 'El apellido es obligatorio.',
         'employee_number.required' => 'El número de empleado es obligatorio.',
         'employee_number.unique' => 'Este número de empleado ya existe.',
         'cedula.required' => 'La cédula es obligatoria.',
         'cedula.unique' => 'Esta cédula ya está registrada.',
         'hire_date.required' => 'La fecha de contratación es obligatoria.',
         'department_id.required' => 'Debe seleccionar un departamento.',
      ];
   }

   /**
    * Prepare the data for validation.
    */
   protected function prepareForValidation(): void {
      if (!$this->filled('password')) {
         $this->merge(['password' => null]); // Se generará automáticamente
      }
   }
}
