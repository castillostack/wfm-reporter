@extends('layouts.app')

@section('title', 'Detalles del Rol')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.roles.index') }}" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                            </a>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $role->name }}</h1>
                                <p class="text-gray-600">Detalles del rol</p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.roles.edit', $role) }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Editar Rol
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Información Principal -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Información Básica -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Información del Rol
                            </h3>
                        </div>
                        <dl class="divide-y divide-gray-200">
                            <div class="px-6 py-4">
                                <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $role->name }}</dd>
                            </div>
                            @if ($role->description)
                                <div class="px-6 py-4">
                                    <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $role->description }}</dd>
                                </div>
                            @endif
                            <div class="px-6 py-4">
                                <dt class="text-sm font-medium text-gray-500">Guard</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $role->guard_name }}</dd>
                            </div>
                            <div class="px-6 py-4">
                                <dt class="text-sm font-medium text-gray-500">Fecha de Creación</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $role->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            @if ($role->updated_at != $role->created_at)
                                <div class="px-6 py-4">
                                    <dt class="text-sm font-medium text-gray-500">Última Modificación</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $role->updated_at->format('d/m/Y H:i') }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Permisos -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Permisos Asignados ({{ $role->permissions->count() }})
                            </h3>
                        </div>
                        @if ($role->permissions->count() > 0)
                            <div class="px-6 py-4">
                                @php
                                    $groupedPermissions = $role->permissions->groupBy(function ($permission) {
                                        return explode('.', $permission->name)[0] ?? 'general';
                                    });
                                @endphp

                                @foreach ($groupedPermissions as $group => $permissions)
                                    <div class="mb-6 last:mb-0">
                                        <h4 class="text-md font-medium text-gray-900 mb-3 capitalize">{{ $group }}
                                        </h4>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($permissions as $permission)
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    {{ $permission->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Sin permisos asignados</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Este rol no tiene permisos específicos asignados.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Estadísticas -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Estadísticas
                            </h3>
                        </div>
                        <div class="px-6 py-4 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Usuarios con este rol</span>
                                <span class="text-sm font-medium text-gray-900">{{ $role->users->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Permisos asignados</span>
                                <span class="text-sm font-medium text-gray-900">{{ $role->permissions->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Usuarios con este rol -->
                    @if ($role->users->count() > 0)
                        <div class="bg-white shadow rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Usuarios con este rol ({{ $role->users->count() }})
                                </h3>
                            </div>
                            <ul class="divide-y divide-gray-200">
                                @foreach ($role->users->take(5) as $user)
                                    <li class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                    <span class="text-xs font-medium text-gray-700">
                                                        {{ substr($user->name, 0, 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                                @if ($role->users->count() > 5)
                                    <li class="px-6 py-4 text-center">
                                        <p class="text-sm text-gray-500">
                                            Y {{ $role->users->count() - 5 }}
                                            usuario{{ $role->users->count() - 5 !== 1 ? 's' : '' }} más...
                                        </p>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif

                    <!-- Acciones -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Acciones
                            </h3>
                        </div>
                        <div class="px-6 py-4 space-y-3">
                            <a href="{{ route('admin.roles.edit', $role) }}"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Editar Rol
                            </a>

                            @if ($role->users->count() === 0)
                                <button type="button" onclick="confirmDelete()"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    Eliminar Rol
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if ($role->users->count() === 0)
        <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Eliminar Rol</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            ¿Estás seguro de que deseas eliminar el rol "{{ $role->name }}"?
                            Esta acción no se puede deshacer.
                        </p>
                    </div>
                    <div class="flex items-center px-4 py-3">
                        <button id="cancelDelete"
                            class="px-4 py-2 bg-gray-300 text-gray-900 text-base font-medium rounded-md w-full mr-2 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Cancelar
                        </button>
                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="w-full ml-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        function confirmDelete() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        @if ($role->users->count() === 0)
            document.getElementById('cancelDelete').addEventListener('click', function() {
                document.getElementById('deleteModal').classList.add('hidden');
            });

            // Close modal when clicking outside
            document.getElementById('deleteModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        @endif
    </script>
@endsection
