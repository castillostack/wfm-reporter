@extends('layouts.app')

@section('title', 'Editar Permiso')

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
                                <h1 class="text-2xl font-bold text-gray-900">Editar Permiso</h1>
                                <p class="text-gray-600">Modifica la información del permiso</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white shadow rounded-lg">
                <form action="{{ route('admin.permissions.update', $permission) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Información del Permiso
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Actualiza los detalles del permiso seleccionado.
                        </p>
                    </div>

                    <div class="px-6 py-4">
                        <!-- Name Field -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre del Permiso <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $permission->name) }}"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-300 @enderror"
                                placeholder="ej: ver-usuarios, crear-roles, etc." required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Usa guiones bajos o guiones para separar palabras. Ejemplo: ver_usuarios, crear-roles
                            </p>
                        </div>

                        <!-- Description Field -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Descripción
                            </label>
                            <textarea id="description" name="description" rows="3"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description') border-red-300 @enderror"
                                placeholder="Describe qué permite hacer este permiso...">{{ old('description', $permission->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Opcional: Proporciona una descripción clara del permiso para facilitar su comprensión.
                            </p>
                        </div>

                        <!-- Permission Category -->
                        <div class="mb-6">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Categoría
                            </label>
                            <select id="category" name="category"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('category') border-red-300 @enderror">
                                <option value="">Seleccionar categoría (opcional)</option>
                                <option value="usuarios"
                                    {{ old('category', $permission->category) == 'usuarios' ? 'selected' : '' }}>Usuarios
                                </option>
                                <option value="roles"
                                    {{ old('category', $permission->category) == 'roles' ? 'selected' : '' }}>Roles</option>
                                <option value="permisos"
                                    {{ old('category', $permission->category) == 'permisos' ? 'selected' : '' }}>Permisos
                                </option>
                                <option value="empleados"
                                    {{ old('category', $permission->category) == 'empleados' ? 'selected' : '' }}>Empleados
                                </option>
                                <option value="departamentos"
                                    {{ old('category', $permission->category) == 'departamentos' ? 'selected' : '' }}>
                                    Departamentos</option>
                                <option value="horarios"
                                    {{ old('category', $permission->category) == 'horarios' ? 'selected' : '' }}>Horarios
                                </option>
                                <option value="reportes"
                                    {{ old('category', $permission->category) == 'reportes' ? 'selected' : '' }}>Reportes
                                </option>
                                <option value="configuracion"
                                    {{ old('category', $permission->category) == 'configuracion' ? 'selected' : '' }}>
                                    Configuración</option>
                                <option value="sistema"
                                    {{ old('category', $permission->category) == 'sistema' ? 'selected' : '' }}>Sistema
                                </option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Opcional: Ayuda a organizar los permisos por funcionalidad.
                            </p>
                        </div>

                        <!-- Associated Roles Info -->
                        @if ($permission->roles->count() > 0)
                            <div class="mb-6">
                                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-blue-800">
                                                Roles Asociados
                                            </h3>
                                            <div class="mt-2 text-sm text-blue-700">
                                                <p>Este permiso está asignado a {{ $permission->roles->count() }}
                                                    rol{{ $permission->roles->count() !== 1 ? 'es' : '' }}:</p>
                                                <ul class="mt-1 list-disc list-inside">
                                                    @foreach ($permission->roles as $role)
                                                        <li>{{ $role->name }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
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
                            Actualizar Permiso
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
