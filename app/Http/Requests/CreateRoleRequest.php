<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRoleRequest extends FormRequest {
   public function authorize(): bool {
      return auth()->check() && auth()->user()->hasRole('analista-wfm');
   }

   public function rules(): array {
      return [
         'name' => 'required|string|max:255|unique:roles,name|regex:/^[a-z-]+$/',
         'description' => 'nullable|string|max:1000',
         'guard_name' => 'nullable|string|max:255',
      ];
   }

   public function messages(): array {
      return [
         'name.required' => 'El nombre del rol es obligatorio.',
         'name.unique' => 'Ya existe un rol con este nombre.',
         'name.regex' => 'El nombre del rol solo puede contener letras minúsculas y guiones.',
         'name.max' => 'El nombre del rol no puede exceder los 255 caracteres.',
         'description.max' => 'La descripción no puede exceder los 1000 caracteres.',
      ];
   }

   public function attributes(): array {
      return [
         'name' => 'nombre del rol',
         'description' => 'descripción',
         'guard_name' => 'guardia',
      ];
   }
}
