<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDepartmentRequest extends FormRequest {
   public function authorize(): bool {
      return auth()->user()->hasRole('analista-wfm');
   }

   public function rules(): array {
      return [
         'name' => 'required|string|max:255|unique:departments,name',
      ];
   }

   public function messages(): array {
      return [
         'name.required' => 'El nombre del departamento es obligatorio.',
         'name.string' => 'El nombre del departamento debe ser una cadena de texto.',
         'name.max' => 'El nombre del departamento no puede exceder los 255 caracteres.',
         'name.unique' => 'Ya existe un departamento con este nombre.',
      ];
   }

   public function attributes(): array {
      return [
         'name' => 'nombre del departamento',
      ];
   }
}
