@extends('layouts.app')

@section('title', 'Detalles de Plantilla de Horario')

@section('content')
    <x-common.page-breadcrumb pageTitle="Detalles de Plantilla" :breadcrumbs="[
        ['name' => 'Horarios', 'url' => route('schedules.index')],
        ['name' => 'Plantillas', 'url' => route('schedules.templates.index')],
        ['name' => $template->name, 'url' => null],
    ]" />

    <div class="space-y-6">
        <!-- Información Principal -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                        {{ $template->name }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Plantilla de horario {{ $template->is_active ? 'activa' : 'inactiva' }}
                    </p>
                </div>
                <div class="flex gap-3">
                    @can('update', $template)
                        <a href="{{ route('schedules.templates.edit', $template) }}"
                            class="rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Editar Plantilla
                        </a>
                    @endcan
                    @can('delete', $template)
                        <form action="{{ route('schedules.templates.destroy', $template) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta plantilla? Esta acción no se puede deshacer.')"
                                class="rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                Eliminar
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

            <!-- Detalles de la Plantilla -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <!-- Horario -->
                <div class="space-y-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Horario</h4>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-100 dark:bg-brand-900/20">
                                <svg class="h-4 w-4 text-brand-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Inicio</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $template->start_time->format('H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-100 dark:bg-brand-900/20">
                                <svg class="h-4 w-4 text-brand-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Fin</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $template->end_time->format('H:i') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/20">
                                <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Duración</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $template->getDurationInHours() }}
                                    horas</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tipo y Estado -->
                <div class="space-y-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Configuración</h4>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/20">
                                <svg class="h-4 w-4 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Tipo</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $template->getTypeLabel() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-lg {{ $template->is_active ? 'bg-green-100 dark:bg-green-900/20' : 'bg-red-100 dark:bg-red-900/20' }}">
                                <svg class="h-4 w-4 {{ $template->is_active ? 'text-green-600' : 'text-red-600' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $template->is_active ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' }}">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Estado</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $template->is_active ? 'Activa' : 'Inactiva' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="space-y-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Estadísticas</h4>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/20">
                                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Usos</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $template->usage_count ?? 0 }}
                                    horarios</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-900/20">
                                <svg class="h-4 w-4 text-orange-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Creada</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $template->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Descripción -->
            @if ($template->description)
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Descripción</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-800/50 rounded-lg p-3">
                        {{ $template->description }}
                    </p>
                </div>
            @endif
        </div>

        <!-- Configuración Avanzada -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">Configuración Avanzada</h4>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Tolerancias -->
                <div>
                    <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Tolerancias</h5>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Entrada</span>
                            <span
                                class="text-sm font-medium text-gray-900 dark:text-white">{{ $template->check_in_tolerance_minutes ?? 15 }}
                                min</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Salida</span>
                            <span
                                class="text-sm font-medium text-gray-900 dark:text-white">{{ $template->check_out_tolerance_minutes ?? 15 }}
                                min</span>
                        </div>
                    </div>
                </div>

                <!-- Días Laborables -->
                <div>
                    <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Días Laborables</h5>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $diasLabels = [
                                'monday' => 'Lunes',
                                'tuesday' => 'Martes',
                                'wednesday' => 'Miércoles',
                                'thursday' => 'Jueves',
                                'friday' => 'Viernes',
                                'saturday' => 'Sábado',
                                'sunday' => 'Domingo',
                            ];
                            $workingDays = $template->working_days ?? [
                                'monday',
                                'tuesday',
                                'wednesday',
                                'thursday',
                                'friday',
                            ];
                        @endphp
                        @foreach ($diasLabels as $key => $label)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ in_array($key, $workingDays) ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                {{ $label }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Horarios Creados con esta Plantilla -->
        @if ($template->schedules && $template->schedules->count() > 0)
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">Horarios Creados con esta Plantilla
                </h4>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Empleado</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Fecha</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Horario</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Estado</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($template->schedules->take(10) as $schedule)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $schedule->employee->full_name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $schedule->employee->department->name ?? 'Sin departamento' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $schedule->date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $schedule->start_time->format('H:i') }} -
                                        {{ $schedule->end_time->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $schedule->getStatusBadgeClass() }}">
                                            {{ $schedule->getStatusLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('schedules.show', $schedule) }}"
                                            class="text-brand-600 hover:text-brand-900 dark:text-brand-400 dark:hover:text-brand-300">
                                            Ver detalles
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($template->schedules->count() > 10)
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Mostrando 10 de {{ $template->schedules->count() }} horarios.
                            <a href="{{ route('schedules.index', ['template_id' => $template->id]) }}"
                                class="text-brand-600 hover:text-brand-900 dark:text-brand-400 dark:hover:text-brand-300">
                                Ver todos
                            </a>
                        </p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Acciones Rápidas -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">Acciones Rápidas</h4>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                <a href="{{ route('schedules.create', ['template_id' => $template->id]) }}"
                    class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 hover:border-brand-300 hover:bg-brand-50 dark:border-gray-700 dark:hover:border-brand-600 dark:hover:bg-brand-900/20 transition-colors">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-100 dark:bg-brand-900/20">
                        <svg class="h-5 w-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Crear Horario</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Usar esta plantilla</p>
                    </div>
                </a>

                <a href="{{ route('schedules.bulk.create') }}"
                    class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 hover:border-green-300 hover:bg-green-50 dark:border-gray-700 dark:hover:border-green-600 dark:hover:bg-green-900/20 transition-colors">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/20">
                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Asignación Masiva</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Aplicar a múltiples empleados</p>
                    </div>
                </a>

                @can('update', $template)
                    <a href="{{ route('schedules.templates.edit', $template) }}"
                        class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 dark:border-gray-700 dark:hover:border-blue-600 dark:hover:bg-blue-900/20 transition-colors">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/20">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Editar Plantilla</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Modificar configuración</p>
                        </div>
                    </a>
                @endcan
            </div>
        </div>
    </div>
@endsection
