@extends('layouts.app')

@section('title', 'Gestión de Empleados')

@section('content')
    <x-common.page-breadcrumb pageTitle="Gestión de Empleados" />

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <!-- Header con acciones -->
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Empleados
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Gestiona la información de todos los empleados de la organización
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <!-- Botones de import/export -->
                <div class="flex gap-2">
                    <form action="{{ route('admin.employees.import') }}" method="POST" enctype="multipart/form-data"
                        class="inline">
                        @csrf
                        <input type="file" id="import-file" name="file" accept=".xlsx,.xls,.csv" class="hidden"
                            onchange="this.form.submit()">
                        <button type="button" onclick="document.getElementById('import-file').click()"
                            class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            Importar
                        </button>
                    </form>

                    <a href="{{ route('admin.employees.export', request()->query()) }}"
                        class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Exportar
                    </a>
                </div>

                <!-- Botón crear -->
                <a href="{{ route('admin.employees.create') }}"
                    class="flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nuevo Empleado
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 rounded-lg bg-green-50 p-4 dark:bg-green-900/20">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 dark:text-green-400">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-lg bg-red-50 p-4 dark:bg-red-900/20">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 dark:text-red-400">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Filtros -->
        <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
            <form method="GET" action="{{ route('admin.employees.index') }}"
                class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                <!-- Búsqueda -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Buscar
                    </label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                        placeholder="Nombre, apellido, email..."
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Departamento -->
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Departamento
                    </label>
                    <select id="department" name="department"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">Todos los departamentos</option>
                        @foreach ($departamentos as $departamento)
                            <option value="{{ $departamento->id }}"
                                {{ request('department') == $departamento->id ? 'selected' : '' }}>
                                {{ $departamento->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Cargo -->
                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Cargo
                    </label>
                    <input type="text" id="position" name="position" value="{{ request('position') }}"
                        placeholder="Ej: Analista, Gerente..."
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>

                <!-- Estado -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Estado
                    </label>
                    <select id="status" name="status"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">Todos</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivos
                        </option>
                    </select>
                </div>

                <!-- Botones -->
                <div class="flex gap-2 md:col-span-2 lg:col-span-4">
                    <button type="submit"
                        class="flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Filtrar
                    </button>

                    <a href="{{ route('admin.employees.index') }}"
                        class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabla de empleados -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Empleado</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Contacto</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Departamento
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Cargo</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Estado</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($empleados as $empleado)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-10 w-10 rounded-full bg-brand-500 flex items-center justify-center text-white font-medium">
                                        {{ substr($empleado->first_name, 0, 1) . substr($empleado->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $empleado->first_name }} {{ $empleado->last_name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            #{{ $empleado->employee_number }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm">
                                    <div class="text-gray-900 dark:text-white">{{ $empleado->email }}</div>
                                    @if ($empleado->phone)
                                        <div class="text-gray-500 dark:text-gray-400">{{ $empleado->phone }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $empleado->departamento ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ $empleado->departamento?->name ?? 'Sin asignar' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $empleado->position }}
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $empleado->deleted_at ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                                    {{ $empleado->deleted_at ? 'Inactivo' : 'Activo' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.employees.show', $empleado->id) }}"
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    <a href="{{ route('admin.employees.edit', $empleado->id) }}"
                                        class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    @if ($empleado->deleted_at)
                                        <form action="{{ route('admin.employees.restore', $empleado->id) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit"
                                                class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.employees.destroy', $empleado->id) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span>No se encontraron empleados</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if ($empleados->hasPages())
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    Mostrando {{ $empleados->firstItem() }} a {{ $empleados->lastItem() }} de {{ $empleados->total() }}
                    empleados
                </div>
                {{ $empleados->appends(request()->query())->links() }}
            </div>
        @endif
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
