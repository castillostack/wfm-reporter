@extends('layouts.app')

@section('title', 'Crear Nuevo Permiso')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.permissions.index') }}" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                            </a>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Crear Nuevo Permiso</h1>
                                <p class="text-gray-600">Agrega un nuevo permiso al sistema</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white shadow rounded-lg">
                <form action="{{ route('admin.permissions.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Información del Permiso
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Completa los detalles del nuevo permiso.
                        </p>
                    </div>

                    <div class="px-6 py-4">
                        <!-- Name Field -->
                        <div class="mb-6">
                            <label for="name" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nombre del Permiso <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('name') border-red-300 @enderror"
                                placeholder="ej: ver-usuarios, crear-roles, etc." required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Usa guiones bajos o guiones para separar palabras. Ejemplo: ver_usuarios, crear-roles
                            </p>
                        </div>

                        <!-- Description Field -->
                        <div class="mb-6">
                            <label for="description"
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Descripción
                            </label>
                            <textarea id="description" name="description" rows="3"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('description') border-red-300 @enderror"
                                placeholder="Describe qué permite hacer este permiso...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Opcional: Proporciona una descripción clara del permiso para facilitar su comprensión.
                            </p>
                        </div>

                        <!-- Permission Category -->
                        <div class="mb-6">
                            <label for="category" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Categoría
                            </label>
                            <select id="category" name="category"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('category') border-red-300 @enderror">
                                <option value="">Seleccionar categoría (opcional)</option>
                                <option value="usuarios" {{ old('category') == 'usuarios' ? 'selected' : '' }}>Usuarios
                                </option>
                                <option value="roles" {{ old('category') == 'roles' ? 'selected' : '' }}>Roles</option>
                                <option value="permisos" {{ old('category') == 'permisos' ? 'selected' : '' }}>Permisos
                                </option>
                                <option value="empleados" {{ old('category') == 'empleados' ? 'selected' : '' }}>Empleados
                                </option>
                                <option value="departamentos" {{ old('category') == 'departamentos' ? 'selected' : '' }}>
                                    Departamentos</option>
                                <option value="horarios" {{ old('category') == 'horarios' ? 'selected' : '' }}>Horarios
                                </option>
                                <option value="reportes" {{ old('category') == 'reportes' ? 'selected' : '' }}>Reportes
                                </option>
                                <option value="configuracion" {{ old('category') == 'configuracion' ? 'selected' : '' }}>
                                    Configuración</option>
                                <option value="sistema" {{ old('category') == 'sistema' ? 'selected' : '' }}>Sistema
                                </option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Opcional: Ayuda a organizar los permisos por funcionalidad.
                            </p>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                        <a href="{{ route('admin.permissions.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Crear Permiso
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-generate name from description or vice versa if needed
        document.getElementById('name').addEventListener('input', function() {
            this.value = this.value.toLowerCase().replace(/[^a-z0-9\-_]/g, '');
        });
    </script>
@endsection
