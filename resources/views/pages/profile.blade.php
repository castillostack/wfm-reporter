@extends('layouts.app')

@section('title', 'Perfil de Usuario')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white dark:bg-gray-dark rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Perfil de Usuario</h1>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf

            <!-- Información del Usuario -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Información de Usuario</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre Completo</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $usuario->name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-brand-500 focus:ring-brand-500">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $usuario->email) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-brand-500 focus:ring-brand-500">
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Creación</label>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $usuario->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Última Actualización</label>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $usuario->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            @if($usuario->empleado)
            <!-- Información del Empleado -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Información de Empleado</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $usuario->empleado->first_name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-brand-500 focus:ring-brand-500">
                        @error('first_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Apellido</label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $usuario->empleado->last_name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-brand-500 focus:ring-brand-500">
                        @error('last_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $usuario->empleado->phone) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-brand-500 focus:ring-brand-500">
                        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Posición</label>
                        <input type="text" id="position" name="position" value="{{ old('position', $usuario->empleado->position) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-brand-500 focus:ring-brand-500">
                        @error('position') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Número de Empleado</label>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $usuario->empleado->employee_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cédula</label>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $usuario->empleado->cedula }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Género</label>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $usuario->empleado->gender }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Nacimiento</label>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $usuario->empleado->birth_date?->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Contratación</label>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $usuario->empleado->hire_date?->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Salario</label>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">${{ number_format($usuario->empleado->salary, 2) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Departamento</label>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $usuario->empleado->departamento?->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Supervisor</label>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $usuario->empleado->supervisor?->name }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-brand-600 border border-transparent rounded-md shadow-sm hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection