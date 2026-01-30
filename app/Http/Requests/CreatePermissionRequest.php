<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePermissionRequest extends FormRequest {
   public function authorize(): bool {
      return auth()->check() && auth()->user()->hasRole('analista-wfm');
   }

   public function rules(): array {
      return [
         'name' => 'required|string|max:255|unique:permissions,name|regex:/^[a-zA-Z0-9\.\-_]+$/',
         'guard_name' => 'nullable|string|max:255',
      ];
   }

   public function messages(): array {
      return [
         'name.required' => 'El nombre del permiso es obligatorio.',
         'name.unique' => 'Ya existe un permiso con este nombre.',
         'name.regex' => 'El nombre del permiso solo puede contener letras, números, puntos, guiones y guiones bajos.',
         'name.max' => 'El nombre del permiso no puede exceder los 255 caracteres.',
      ];
   }

   public function attributes(): array {
      return [
         'name' => 'nombre del permiso',
         'guard_name' => 'guardia',
      ];
   }
}
