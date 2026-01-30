@extends('layouts.app')

@section('title', 'Crear Horario')

@section('content')
    <x-common.page-breadcrumb pageTitle="Crear Horario" :breadcrumbs="[['name' => 'Horarios', 'url' => route('schedules.index')], ['name' => 'Crear', 'url' => null]]" />

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Crear Nuevo Horario
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Asigna un horario de trabajo a un empleado
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

        <form action="{{ route('schedules.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Empleado -->
                <div>
                    <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Empleado <span class="text-red-500">*</span>
                    </label>
                    <select id="employee_id" name="employee_id" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('employee_id') border-red-500 @enderror">
                        <option value="">Seleccionar empleado...</option>
                        @foreach ($empleados as $empleado)
                            <option value="{{ $empleado->id }}" {{ old('employee_id') == $empleado->id ? 'selected' : '' }}>
                                {{ $empleado->first_name }} {{ $empleado->last_name }} -
                                {{ $empleado->department->name ?? 'Sin departamento' }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha -->
                <div>
                    <label for="scheduled_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Fecha <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="scheduled_date" name="scheduled_date"
                        value="{{ old('scheduled_date', now()->format('Y-m-d')) }}" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('scheduled_date') border-red-500 @enderror">
                    @error('scheduled_date')
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

                <!-- Plantilla -->
                <div>
                    <label for="schedule_template_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Plantilla (Opcional)
                    </label>
                    <select id="schedule_template_id" name="schedule_template_id"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">Sin plantilla</option>
                        @foreach ($plantillas as $plantilla)
                            <option value="{{ $plantilla->id }}"
                                {{ old('schedule_template_id') == $plantilla->id ? 'selected' : '' }}>
                                {{ $plantilla->name }} ({{ $plantilla->start_time->format('H:i') }} -
                                {{ $plantilla->end_time->format('H:i') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Estado -->
                <div>
                    <label for="is_published" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Estado
                    </label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="is_published" value="0"
                                {{ old('is_published', '0') == '0' ? 'checked' : '' }}
                                class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Borrador</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="is_published" value="1"
                                {{ old('is_published') == '1' ? 'checked' : '' }}
                                class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Publicado</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Notas -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Notas
                </label>
                <textarea id="notes" name="notes" rows="3" placeholder="Notas adicionales sobre este horario..."
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Conflictos -->
            <div id="conflicts-section"
                class="hidden rounded-lg border border-yellow-200 bg-yellow-50 p-4 dark:border-yellow-800 dark:bg-yellow-900/20">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                            Posibles Conflictos Detectados
                        </h3>
                        <div id="conflicts-list" class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                            <!-- Los conflictos se cargarán aquí vía JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('schedules.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    Cancelar
                </a>
                <button type="submit"
                    class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                    Crear Horario
                </button>
            </div>
        </form>
    </div>

    <script>
        // Función para detectar conflictos
        function checkConflicts() {
            const employeeId = document.getElementById('employee_id').value;
            const scheduledDate = document.getElementById('scheduled_date').value;
            const startTime = document.getElementById('start_time').value;
            const endTime = document.getElementById('end_time').value;

            if (employeeId && scheduledDate && startTime && endTime) {
                // Aquí iría la lógica para verificar conflictos vía AJAX
                // Por ahora, solo mostramos el mensaje si las horas se solapan
                const start = new Date(`2000-01-01T${startTime}`);
                const end = new Date(`2000-01-01T${endTime}`);

                if (start >= end) {
                    showConflicts(['La hora de fin debe ser posterior a la hora de inicio']);
                } else {
                    hideConflicts();
                }
            } else {
                hideConflicts();
            }
        }

        function showConflicts(conflicts) {
            const section = document.getElementById('conflicts-section');
            const list = document.getElementById('conflicts-list');

            list.innerHTML = conflicts.map(conflict => `<p>• ${conflict}</p>`).join('');
            section.classList.remove('hidden');
        }

        function hideConflicts() {
            const section = document.getElementById('conflicts-section');
            section.classList.add('hidden');
        }

        // Event listeners
        document.getElementById('employee_id').addEventListener('change', checkConflicts);
        document.getElementById('scheduled_date').addEventListener('change', checkConflicts);
        document.getElementById('start_time').addEventListener('change', checkConflicts);
        document.getElementById('end_time').addEventListener('change', checkConflicts);

        // Cargar plantilla automáticamente
        document.getElementById('schedule_template_id').addEventListener('change', function() {
            const templateId = this.value;
            if (templateId) {
                // Aquí iría la lógica para cargar los datos de la plantilla vía AJAX
                // Por simplicidad, asumimos que los datos están disponibles
            }
        });
    </script>
@endsection
