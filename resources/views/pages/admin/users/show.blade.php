@extends('layouts.app')

@section('title', 'Detalles del Usuario')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-xl font-medium text-gray-700">
                                {{ substr($user->name, 0, 1) . substr($user->employee?->last_name ?? '', 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}
                                {{ $user->employee?->last_name }}</h1>
                            <p class="text-gray-600">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.users.edit', $user) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Editar
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            ← Volver
                        </a>
                    </div>
                </div>
            </div>

            <div class="px-6 py-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Información Personal -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información Personal</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre completo</dt>
                                <dd class="text-sm text-gray-900">{{ $user->name }} {{ $user->employee?->last_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                <dd class="text-sm text-gray-900">{{ $user->employee?->phone ?? 'No especificado' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                <dd class="text-sm">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $user->trashed() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $user->trashed() ? 'Inactivo' : 'Activo' }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Último acceso</dt>
                                <dd class="text-sm text-gray-900">
                                    {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de registro</dt>
                                <dd class="text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Información Laboral -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información Laboral</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ID Empleado</dt>
                                <dd class="text-sm text-gray-900">{{ $user->employee?->employee_number ?? 'No asignado' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Departamento</dt>
                                <dd class="text-sm text-gray-900">
                                    {{ $user->employee?->department?->name ?? 'Sin asignar' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Cargo</dt>
                                <dd class="text-sm text-gray-900">{{ $user->employee?->position ?? 'No especificado' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de contratación</dt>
                                <dd class="text-sm text-gray-900">
                                    {{ $user->employee?->hire_date ? $user->employee->hire_date->format('d/m/Y') : 'No especificada' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Rol en el sistema</dt>
                                <dd class="text-sm">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($user->hasRole('analista-wfm')) bg-purple-100 text-purple-800
                                    @elseif($user->hasRole('director-nacional')) bg-red-100 text-red-800
                                    @elseif($user->hasRole('jefe-departamento')) bg-blue-100 text-blue-800
                                    @elseif($user->hasRole('coordinador')) bg-yellow-100 text-yellow-800
                                    @elseif($user->hasRole('operador')) bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('-', ' ', $user->getRoleNames()->first() ?? 'sin-rol')) }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Permisos -->
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Permisos</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($user->getAllPermissions() as $permission)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                            </div>
                        @endforeach
                        @if ($user->getAllPermissions()->isEmpty())
                            <p class="text-sm text-gray-500 col-span-full">No tiene permisos asignados</p>
                        @endif
                    </div>
                </div>

                <!-- Acciones -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.users.edit', $user) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Editar Usuario
                        </a>
                        @if ($user->trashed())
                            <form method="POST" action="{{ route('admin.users.restore', $user) }}" class="inline">
                                @csrf
                                @method('POST')
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Restaurar Usuario
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Desactivar Usuario
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar eliminación de usuarios con SweetAlert
    document.querySelectorAll('form[action*="destroy"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: '¿Deseas desactivar este usuario? Esto lo marcará como inactivo pero no lo eliminará permanentemente.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    // Manejar restauración de usuarios con SweetAlert
    document.querySelectorAll('form[action*="restore"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: '¿Deseas restaurar este usuario?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, restaurar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
});
</script>
@endpush
