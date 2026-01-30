@extends('layouts.app')

@section('title', 'Detalles del Horario')

@section('content')
    <x-common.page-breadcrumb pageTitle="Detalles del Horario" :breadcrumbs="[['name' => 'Horarios', 'url' => route('schedules.index')], ['name' => 'Detalles', 'url' => null]]" />

    <div class="space-y-6">
        <!-- Información Principal -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                        Información del Horario
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Detalles del horario asignado
                    </p>
                </div>

                <div class="flex gap-2">
                    @if (!$horario->is_published)
                        <form action="{{ route('schedules.publish', $horario) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-lg bg-green-500 px-4 py-2 text-sm font-medium text-white hover:bg-green-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Publicar
                            </button>
                        </form>
                    @else
                        <form action="{{ route('schedules.unpublish', $horario) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-lg bg-yellow-500 px-4 py-2 text-sm font-medium text-white hover:bg-yellow-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Despublicar
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('schedules.edit', $horario) }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <!-- Empleado -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Empleado</label>
                    <div class="flex items-center gap-3">
                        <div
                            class="h-10 w-10 rounded-full bg-brand-500 flex items-center justify-center text-white font-medium">
                            {{ substr($horario->employee->first_name, 0, 1) . substr($horario->employee->last_name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $horario->employee->first_name }} {{ $horario->employee->last_name }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $horario->employee->department->name ?? 'Sin departamento' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fecha -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Fecha</label>
                    <div class="text-gray-900 dark:text-white">
                        {{ $horario->scheduled_date->format('l, d F Y') }}
                    </div>
                </div>

                <!-- Horario -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Horario</label>
                    <div class="text-gray-900 dark:text-white">
                        {{ $horario->start_time->format('H:i') }} - {{ $horario->end_time->format('H:i') }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Duración: {{ $horario->scheduled_duration }} horas
                    </div>
                </div>

                <!-- Estado -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Estado</label>
                    @if ($horario->is_published)
                        <span
                            class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/20 dark:text-green-400">
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Publicado
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd" />
                            </svg>
                            Borrador
                        </span>
                    @endif
                </div>

                <!-- Plantilla -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Plantilla</label>
                    <div class="text-gray-900 dark:text-white">
                        @if ($horario->scheduleTemplate)
                            {{ $horario->scheduleTemplate->name }}
                        @else
                            <span class="text-gray-500 dark:text-gray-400">Sin plantilla</span>
                        @endif
                    </div>
                </div>

                <!-- Creado por -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Creado por</label>
                    <div class="text-gray-900 dark:text-white">
                        {{ $horario->creator->name ?? 'Sistema' }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $horario->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>

            <!-- Notas -->
            @if ($horario->notes)
                <div class="mt-6 space-y-2">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Notas</label>
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                        <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $horario->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Asistencia Relacionada -->
        @if ($horario->attendanceLogs->count() > 0)
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                        Registros de Asistencia
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Marcas de entrada y salida para este horario
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Tipo
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Hora
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">
                                    Ubicación</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($horario->attendanceLogs as $log)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                                        @if ($log->type === 'check_in')
                                            <span class="inline-flex items-center gap-1 text-green-600 dark:text-green-400">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                                </svg>
                                                Entrada
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-blue-600 dark:text-blue-400">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                </svg>
                                                Salida
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                                        {{ $log->timestamp->format('H:i:s') }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                                        {{ $log->location ?? 'No especificada' }}
                                    </td>
                                    <td class="px-4 py-4">
                                        @if ($log->is_valid)
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                                Válido
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900/20 dark:text-red-400">
                                                Inválido
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Conflictos -->
        @if ($horario->scheduleConflicts->count() > 0)
            <div class="rounded-2xl border border-red-200 bg-red-50 p-5 dark:border-red-800 dark:bg-red-900/20 lg:p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-red-800 dark:text-red-200">
                        Conflictos Detectados
                    </h3>
                    <p class="mt-1 text-sm text-red-700 dark:text-red-300">
                        Se encontraron conflictos con este horario
                    </p>
                </div>

                <div class="space-y-4">
                    @foreach ($horario->scheduleConflicts as $conflict)
                        <div class="rounded-lg border border-red-200 bg-white p-4 dark:border-red-700 dark:bg-gray-800">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0">
                                    @if ($conflict->severity === 'high')
                                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @elseif($conflict->severity === 'medium')
                                        <svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $conflict->getTypeLabel() }}
                                    </h4>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $conflict->description }}
                                    </p>
                                    <div class="mt-2 flex items-center gap-2">
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full {{ $conflict->getSeverityColor() }} px-2.5 py-0.5 text-xs font-medium">
                                            {{ ucfirst($conflict->severity) }}
                                        </span>
                                        @if ($conflict->resolved_at)
                                            <span class="text-sm text-green-600 dark:text-green-400">
                                                Resuelto el {{ $conflict->resolved_at->format('d/m/Y H:i') }}
                                            </span>
                                        @else
                                            <button type="button" onclick="resolveConflict({{ $conflict->id }})"
                                                class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                Marcar como resuelto
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        function resolveConflict(conflictId) {
            if (confirm('¿Estás seguro de que quieres marcar este conflicto como resuelto?')) {
                // Aquí iría la lógica para resolver el conflicto vía AJAX
                // Por ahora, recargamos la página
                window.location.reload();
            }
        }
    </script>
@endsection
