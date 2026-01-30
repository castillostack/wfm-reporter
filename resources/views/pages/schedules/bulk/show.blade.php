@extends('layouts.app')

@section('title', 'Detalles de Asignación Masiva')

@section('content')
    <x-common.page-breadcrumb pageTitle="Detalles de Asignación Masiva" :breadcrumbs="[
        ['name' => 'Horarios', 'url' => route('schedules.index')],
        ['name' => 'Asignaciones Masivas', 'url' => route('schedules.bulk.index')],
        ['name' => 'Detalles', 'url' => null],
    ]" />

    <div class="space-y-6">
        <!-- Información Principal -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                        Información de la Asignación
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Detalles y resultados de la asignación masiva
                    </p>
                </div>
            </div>

            <!-- Mensaje cuando no hay asignación específica -->
            <div class="rounded-lg bg-blue-50 p-6 dark:bg-blue-900/20">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                            Información no disponible
                        </h3>
                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                            <p>Las asignaciones masivas no se almacenan como registros individuales en el sistema. Esta
                                vista está disponible para futuras implementaciones donde se pueda rastrear el historial
                                detallado de asignaciones.</p>
                            <p class="mt-2">ID de referencia: <code
                                    class="bg-blue-100 px-1 py-0.5 rounded text-xs">{{ $assignment_id }}</code></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('schedules.bulk.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Crear Nueva Asignación Masiva
                </a>
            </div>
        </div>
    </div>
@endsection

<!-- Creado por -->
<div class="space-y-2">
    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Creado por</label>
    <div class="text-gray-900 dark:text-white">
        {{ $asignacion->creator->name ?? 'Sistema' }}
    </div>
</div>
</div>

<!-- Estadísticas -->
<div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-4">
    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/20">
                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Empleados Objetivo</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ $asignacion->target_employees_count }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/20">
                <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Asignaciones Exitosas</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ $asignacion->successful_assignments }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/20">
                <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Asignaciones Fallidas</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $asignacion->failed_assignments }}
                </p>
            </div>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-yellow-100 dark:bg-yellow-900/20">
                <svg class="h-5 w-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Conflictos</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ $asignacion->conflicts_count ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Filtros Aplicados -->
@if ($asignacion->filters)
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Filtros Aplicados
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Criterios utilizados para seleccionar empleados
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            @if (isset($asignacion->filters['department_id']))
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Departamento</label>
                    <div class="text-gray-900 dark:text-white">
                        {{ $departamentos->find($asignacion->filters['department_id'])->name ?? 'No encontrado' }}
                    </div>
                </div>
            @endif

            @if (isset($asignacion->filters['position']) && $asignacion->filters['position'])
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Cargo</label>
                    <div class="text-gray-900 dark:text-white">{{ $asignacion->filters['position'] }}</div>
                </div>
            @endif

            @if (isset($asignacion->filters['employee_status']))
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Estado</label>
                    <div class="text-gray-900 dark:text-white">
                        {{ $asignacion->filters['employee_status'] === 'active' ? 'Activos' : 'Inactivos' }}
                    </div>
                </div>
            @endif

            @if (isset($asignacion->filters['employee_ids']) && count($asignacion->filters['employee_ids']) > 0)
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Empleados
                        Específicos</label>
                    <div class="text-gray-900 dark:text-white">
                        {{ count($asignacion->filters['employee_ids']) }}
                        empleado{{ count($asignacion->filters['employee_ids']) !== 1 ? 's' : '' }}
                        seleccionado{{ count($asignacion->filters['employee_ids']) !== 1 ? 's' : '' }}
                    </div>
                </div>
            @endif
        </div>

        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Sobrescribir
                    Existentes</label>
                <div class="text-gray-900 dark:text-white">
                    {{ $asignacion->filters['overwrite_existing'] ?? false ? 'Sí' : 'No' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Omitir Conflictos</label>
                <div class="text-gray-900 dark:text-white">
                    {{ $asignacion->filters['skip_conflicts'] ?? true ? 'Sí' : 'No' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Publicar
                    Automáticamente</label>
                <div class="text-gray-900 dark:text-white">
                    {{ $asignacion->filters['publish_schedules'] ?? false ? 'Sí' : 'No' }}
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Resultados de Asignación -->
@if ($asignacion->assignment_results && count($asignacion->assignment_results) > 0)
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Resultados de Asignación
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Detalle de cada asignación realizada
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">
                            Empleado</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">
                            Resultado</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">
                            Mensaje</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">
                            Horario Creado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($asignacion->assignment_results as $resultado)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $resultado['employee_name'] ?? 'Empleado no encontrado' }}
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                @if ($resultado['success'])
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Exitoso
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900/20 dark:text-red-400">
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Fallido
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $resultado['message'] ?? 'Sin mensaje' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                                @if (isset($resultado['schedule_id']))
                                    <a href="{{ route('schedules.show', $resultado['schedule_id']) }}"
                                        class="text-brand-600 hover:text-brand-900 dark:text-brand-400 dark:hover:text-brand-300">
                                        Ver Horario
                                    </a>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
</div>
@endsection
