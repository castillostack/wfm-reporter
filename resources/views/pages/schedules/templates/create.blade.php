@extends('layouts.app')

@section('title', 'Crear Plantilla de Horario')

@section('content')
    <x-common.page-breadcrumb pageTitle="Crear Plantilla de Horario" :breadcrumbs="[
        ['name' => 'Horarios', 'url' => route('schedules.index')],
        ['name' => 'Plantillas', 'url' => route('schedules.templates.index')],
        ['name' => 'Crear', 'url' => null],
    ]" />

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Crear Nueva Plantilla
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Define una plantilla reutilizable para crear horarios de trabajo
            </p>
        </div>

        <!-- Flash Messages -->
        @if (session('error'))
            <div class="mb-6 rounded-lg bg-red-50 p-4 dark:bg-red-900/20">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 dark:text-red-400">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('schedules.templates.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Nombre -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nombre de la Plantilla <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        placeholder="Ej: Jornada Estándar, Turno Mañana, Medio Tiempo..."
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror"
                        required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hora de inicio -->
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Hora de Inicio <span class="text-red-500">*</span>
                    </label>
                    <input type="time" id="start_time" name="start_time" value="{{ old('start_time', '09:00') }}"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('start_time') border-red-500 @enderror">
                    @error('start_time')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hora de fin -->
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Hora de Fin <span class="text-red-500">*</span>
                    </label>
                    <input type="time" id="end_time" name="end_time" value="{{ old('end_time', '17:00') }}" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('end_time') border-red-500 @enderror">
                    @error('end_time')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tipo de Plantilla <span class="text-red-500">*</span>
                    </label>
                    <select id="type" name="type" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('type') border-red-500 @enderror">
                        <option value="">Seleccionar tipo...</option>
                        <option value="standard" {{ old('type') === 'standard' ? 'selected' : '' }}>Estándar (Jornada
                            completa)</option>
                        <option value="shift" {{ old('type') === 'shift' ? 'selected' : '' }}>Turno (Trabajo por turnos)
                        </option>
                        <option value="flexible" {{ old('type') === 'flexible' ? 'selected' : '' }}>Flexible (Horario
                            variable)</option>
                        <option value="part-time" {{ old('type') === 'part-time' ? 'selected' : '' }}>Medio Tiempo</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Estado
                    </label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="is_active" value="1"
                                {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Activa</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="is_active" value="0"
                                {{ old('is_active') == '0' ? 'checked' : '' }}
                                class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Inactiva</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Descripción -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Descripción
                </label>
                <textarea id="description" name="description" rows="3"
                    placeholder="Describe el propósito y características de esta plantilla..."
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Configuración adicional -->
            <div class="space-y-4">
                <h4 class="text-md font-medium text-gray-900 dark:text-white">Configuración Adicional</h4>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- Tolerancia de entrada (minutos) -->
                    <div>
                        <label for="check_in_tolerance_minutes"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tolerancia de Entrada (minutos)
                        </label>
                        <input type="number" id="check_in_tolerance_minutes" name="check_in_tolerance_minutes"
                            value="{{ old('check_in_tolerance_minutes', 15) }}" min="0" max="120"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Minutos de tolerancia para marcar entrada antes/despues de la hora programada
                        </p>
                    </div>

                    <!-- Tolerancia de salida (minutos) -->
                    <div>
                        <label for="check_out_tolerance_minutes"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tolerancia de Salida (minutos)
                        </label>
                        <input type="number" id="check_out_tolerance_minutes" name="check_out_tolerance_minutes"
                            value="{{ old('check_out_tolerance_minutes', 15) }}" min="0" max="120"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Minutos de tolerancia para marcar salida antes/despues de la hora programada
                        </p>
                    </div>

                    <!-- Días laborables -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Días Laborables
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                            Selecciona los días de la semana en que aplica esta plantilla
                        </p>
                        <div class="grid grid-cols-2 gap-3 md:grid-cols-7">
                            @php
                                $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                                $diasValues = [
                                    'monday',
                                    'tuesday',
                                    'wednesday',
                                    'thursday',
                                    'friday',
                                    'saturday',
                                    'sunday',
                                ];
                                $diasSeleccionados = old('working_days', [
                                    'monday',
                                    'tuesday',
                                    'wednesday',
                                    'thursday',
                                    'friday',
                                ]);
                            @endphp
                            @foreach ($dias as $index => $dia)
                                <label class="flex items-center">
                                    <input type="checkbox" name="working_days[]" value="{{ $diasValues[$index] }}"
                                        {{ in_array($diasValues[$index], $diasSeleccionados) ? 'checked' : '' }}
                                        class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $dia }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vista previa -->
            <div id="template-preview"
                class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Vista Previa de la Plantilla</h5>
                <div id="preview-details" class="text-sm text-gray-600 dark:text-gray-300">
                    <!-- La vista previa se actualizará dinámicamente -->
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('schedules.templates.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    Cancelar
                </a>
                <button type="submit"
                    class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                    Crear Plantilla
                </button>
            </div>
        </form>
    </div>

    <script>
        function updatePreview() {
            const name = document.getElementById('name').value;
            const startTime = document.getElementById('start_time').value;
            const endTime = document.getElementById('end_time').value;
            const type = document.getElementById('type').value;

            let preview = '';

            if (name) {
                preview += `<div><strong>Nombre:</strong> ${name}</div>`;
            }

            if (startTime && endTime) {
                const start = new Date(`2000-01-01T${startTime}`);
                const end = new Date(`2000-01-01T${endTime}`);
                const duration = (end - start) / (1000 * 60 * 60); // horas

                preview += `<div><strong>Horario:</strong> ${startTime} - ${endTime} (${duration.toFixed(1)} horas)</div>`;
            }

            if (type) {
                const typeLabels = {
                    'standard': 'Estándar',
                    'shift': 'Turno',
                    'flexible': 'Flexible',
                    'part-time': 'Medio Tiempo'
                };
                preview += `<div><strong>Tipo:</strong> ${typeLabels[type] || type}</div>`;
            }

            // Calcular días laborables
            const workingDaysCheckboxes = document.querySelectorAll('input[name="working_days[]"]:checked');
            const workingDays = Array.from(workingDaysCheckboxes).map(cb => {
                const labels = {
                    'monday': 'Lun',
                    'tuesday': 'Mar',
                    'wednesday': 'Mié',
                    'thursday': 'Jue',
                    'friday': 'Vie',
                    'saturday': 'Sáb',
                    'sunday': 'Dom'
                };
                return labels[cb.value] || cb.value;
            });

            if (workingDays.length > 0) {
                preview += `<div><strong>Días:</strong> ${workingDays.join(', ')}</div>`;
            }

            document.getElementById('preview-details').innerHTML = preview ||
                '<em>Complete los campos para ver la vista previa</em>';
        }

        // Event listeners
        document.getElementById('name').addEventListener('input', updatePreview);
        document.getElementById('start_time').addEventListener('change', updatePreview);
        document.getElementById('end_time').addEventListener('change', updatePreview);
        document.getElementById('type').addEventListener('change', updatePreview);

        // Event listeners para días laborables
        document.querySelectorAll('input[name="working_days[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', updatePreview);
        });

        // Inicializar vista previa
        updatePreview();
    </script>
@endsection
