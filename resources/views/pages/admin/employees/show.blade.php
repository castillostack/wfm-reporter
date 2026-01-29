@extends('layouts.app')

@section('title', 'Detalles del Empleado')

@section('content')
    <x-common.page-breadcrumb pageTitle="Detalles del Empleado" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <!-- Información principal -->
        <div class="lg:col-span-8">
            <div class="space-y-6">
                <!-- Header del empleado -->
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                    <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                        <div class="flex w-full flex-col items-center gap-6 xl:flex-row">
                            <div
                                class="h-20 w-20 overflow-hidden rounded-full border border-gray-200 bg-brand-500 text-white flex items-center justify-center text-3xl font-bold dark:border-gray-800">
                                {{ substr($empleado->first_name, 0, 1) . substr($empleado->last_name, 0, 1) }}
                            </div>
                            <div class="order-3 xl:order-2 flex-1">
                                <h4
                                    class="mb-2 text-center text-xl font-semibold text-gray-800 xl:text-left dark:text-white/90">
                                    {{ $empleado->first_name }} {{ $empleado->last_name }}
                                </h4>
                                <div class="flex flex-col items-center gap-1 text-center xl:flex-row xl:gap-3 xl:text-left">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $empleado->position }}
                                    </p>
                                    <div class="hidden h-3.5 w-px bg-gray-300 xl:block dark:bg-gray-700"></div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        #{{ $empleado->employee_number }}
                                    </p>
                                </div>
                                <div class="mt-2 flex flex-col items-center gap-2 xl:flex-row xl:items-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $empleado->deleted_at ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                                        {{ $empleado->deleted_at ? 'Inactivo' : 'Activo' }}
                                    </span>
                                    @if ($empleado->usuario)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            Usuario Asociado
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="order-2 flex gap-2 xl:order-3 xl:justify-end">
                            <a href="{{ route('admin.employees.edit', $empleado->id) }}"
                                class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Editar
                            </a>

                            @if ($empleado->deleted_at)
                                <form action="{{ route('admin.employees.restore', $empleado->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('POST')
                                    <button type="submit"
                                        class="flex items-center gap-2 rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Reactivar
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.employees.destroy', $empleado->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="flex items-center gap-2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Desactivar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Información de contacto -->
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                    <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">
                        Información de Contacto
                    </h3>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Correo Electrónico
                            </label>
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $empleado->email }}</span>
                            </div>
                        </div>

                        @if ($empleado->phone)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Teléfono
                                </label>
                                <div class="flex items-center gap-2">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span class="text-sm text-gray-900 dark:text-white">{{ $empleado->phone }}</span>
                                </div>
                            </div>
                        @endif

                        @if ($empleado->address)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Dirección
                                </label>
                                <div class="flex items-start gap-2">
                                    <svg class="h-4 w-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-sm text-gray-900 dark:text-white">{{ $empleado->address }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Información laboral -->
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                    <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">
                        Información Laboral
                    </h3>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Cargo
                            </label>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $empleado->position }}</span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Departamento
                            </label>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ $empleado->departamento?->name ?? 'Sin asignar' }}
                            </span>
                        </div>

                        @if ($empleado->supervisor)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Supervisor
                                </label>
                                <div class="flex items-center gap-2">
                                    <div
                                        class="h-6 w-6 rounded-full bg-brand-500 flex items-center justify-center text-white text-xs font-medium">
                                        {{ substr($empleado->supervisor->first_name, 0, 1) . substr($empleado->supervisor->last_name, 0, 1) }}
                                    </div>
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ $empleado->supervisor->first_name }} {{ $empleado->supervisor->last_name }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        @if ($empleado->hire_date)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Fecha de Contratación
                                </label>
                                <span
                                    class="text-sm text-gray-900 dark:text-white">{{ $empleado->hire_date->format('d/m/Y') }}</span>
                            </div>
                        @endif

                        @if ($empleado->salary)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Salario
                                </label>
                                <span
                                    class="text-sm text-gray-900 dark:text-white">${{ number_format($empleado->salary, 2) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Contacto de emergencia -->
                @if ($empleado->emergency_contact_name || $empleado->emergency_contact_phone)
                    <div
                        class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                        <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">
                            Contacto de Emergencia
                        </h3>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            @if ($empleado->emergency_contact_name)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nombre
                                    </label>
                                    <span
                                        class="text-sm text-gray-900 dark:text-white">{{ $empleado->emergency_contact_name }}</span>
                                </div>
                            @endif

                            @if ($empleado->emergency_contact_phone)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Teléfono
                                    </label>
                                    <span
                                        class="text-sm text-gray-900 dark:text-white">{{ $empleado->emergency_contact_phone }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Subordinados -->
                @if ($empleado->subordinados && $empleado->subordinados->count() > 0)
                    <div
                        class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                        <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">
                            Equipo a Cargo ({{ $empleado->subordinados->count() }})
                        </h3>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                            @foreach ($empleado->subordinados as $subordinado)
                                <div
                                    class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                                    <div
                                        class="h-10 w-10 rounded-full bg-brand-500 flex items-center justify-center text-white font-medium">
                                        {{ substr($subordinado->first_name, 0, 1) . substr($subordinado->last_name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $subordinado->first_name }} {{ $subordinado->last_name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                            {{ $subordinado->position }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Panel lateral -->
        <div class="lg:col-span-4">
            <div class="space-y-6">
                <!-- Usuario asociado -->
                @if ($empleado->usuario)
                    <div
                        class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                        <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">
                            Usuario Asociado
                        </h4>

                        <div class="flex items-center gap-3">
                            <div
                                class="h-12 w-12 rounded-full bg-brand-500 flex items-center justify-center text-white font-medium text-lg">
                                {{ substr($empleado->usuario->name, 0, 1) }}
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900 dark:text-white">{{ $empleado->usuario->name }}</h5>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $empleado->usuario->email }}</p>
                                <div class="mt-1">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $empleado->usuario->hasRole('analista-wfm') ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $empleado->usuario->hasRole('analista-wfm') ? 'Analista WFM' : 'Usuario' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Información del sistema -->
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                    <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">
                        Información del Sistema
                    </h4>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">ID Empleado</span>
                            <span class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $empleado->id }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Número Empleado</span>
                            <span
                                class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $empleado->employee_number }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Fecha Creación</span>
                            <span
                                class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $empleado->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Última Actualización</span>
                            <span
                                class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $empleado->updated_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Acciones rápidas -->
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                    <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">
                        Acciones Rápidas
                    </h4>

                    <div class="space-y-3">
                        <a href="{{ route('admin.employees.edit', $empleado) }}"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Editar Empleado
                        </a>

                        <a href="{{ route('admin.employees.index') }}"
                            class="flex w-full items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Volver al Listado
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar eliminación de empleados con SweetAlert
    document.querySelectorAll('form[action*="destroy"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: '¿Deseas desactivar este empleado? Esto también desactivará al usuario asociado si existe.',
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

    // Manejar restauración de empleados con SweetAlert
    document.querySelectorAll('form[action*="restore"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: '¿Deseas restaurar este empleado?',
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
