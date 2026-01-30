@extends('layouts.app')

@section('title', 'Nueva Asignación Masiva')

@section('content')
    <x-common.page-breadcrumb pageTitle="Nueva Asignación Masiva" :breadcrumbs="[
        ['name' => 'Horarios', 'url' => route('schedules.index')],
        ['name' => 'Asignaciones Masivas', 'url' => route('schedules.bulk.index')],
        ['name' => 'Crear', 'url' => null],
    ]" />

    <div class="space-y-6">
        <!-- Información del proceso -->
        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5 dark:border-blue-800 dark:bg-blue-900/20 lg:p-6">
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
                        Proceso de Asignación Masiva
                    </h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <ol class="list-decimal list-inside space-y-1">
                            <li>Selecciona una plantilla de horario</li>
                            <li>Configura los filtros para seleccionar empleados</li>
                            <li>Previsualiza la asignación antes de confirmar</li>
                            <li>Revisa los resultados y conflictos detectados</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Configurar Asignación Masiva
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Selecciona la plantilla y configura los criterios para asignar horarios
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

            <form id="bulk-form" action="{{ route('schedules.bulk.preview') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Paso 1: Seleccionar Plantilla -->
                <div class="space-y-4">
                    <h4 class="text-md font-medium text-gray-900 dark:text-white">1. Seleccionar Plantilla</h4>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="schedule_template_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Plantilla de Horario <span class="text-red-500">*</span>
                            </label>
                            <select id="schedule_template_id" name="schedule_template_id" required
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('schedule_template_id') border-red-500 @enderror">
                                <option value="">Seleccionar plantilla...</option>
                                @foreach ($plantillas as $plantilla)
                                    <option value="{{ $plantilla->id }}"
                                        {{ old('schedule_template_id') == $plantilla->id ? 'selected' : '' }}>
                                        {{ $plantilla->name }} ({{ $plantilla->start_time->format('H:i') }} -
                                        {{ $plantilla->end_time->format('H:i') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('schedule_template_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Fecha de Inicio <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="start_date" name="start_date"
                                value="{{ old('start_date', now()->format('Y-m-d')) }}" required
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('start_date') border-red-500 @enderror">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Vista previa de la plantilla -->
                    <div id="template-preview"
                        class="hidden rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                        <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Vista Previa de la Plantilla</h5>
                        <div id="template-details" class="text-sm text-gray-600 dark:text-gray-300">
                            <!-- Los detalles se cargarán vía JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Paso 2: Configurar Filtros -->
                <div class="space-y-4">
                    <h4 class="text-md font-medium text-gray-900 dark:text-white">2. Configurar Filtros de Empleados</h4>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <!-- Departamento -->
                        <div>
                            <label for="department_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Departamento
                            </label>
                            <select id="department_id" name="department_id"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Todos los departamentos</option>
                                @foreach ($departamentos as $departamento)
                                    <option value="{{ $departamento->id }}"
                                        {{ old('department_id') == $departamento->id ? 'selected' : '' }}>
                                        {{ $departamento->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Cargo -->
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Cargo
                            </label>
                            <input type="text" id="position" name="position" value="{{ old('position') }}"
                                placeholder="Ej: Analista, Gerente..."
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- Estado -->
                        <div>
                            <label for="employee_status"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Estado del Empleado
                            </label>
                            <select id="employee_status" name="employee_status"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Todos</option>
                                <option value="active" {{ old('employee_status') === 'active' ? 'selected' : '' }}>Activos
                                </option>
                                <option value="inactive" {{ old('employee_status') === 'inactive' ? 'selected' : '' }}>
                                    Inactivos</option>
                            </select>
                        </div>
                    </div>

                    <!-- Selección específica de empleados -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Empleados Específicos (Opcional)
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                            Si no seleccionas empleados específicos, se aplicarán los filtros anteriores a todos los
                            empleados que coincidan.
                        </p>
                        <select id="employee_ids" name="employee_ids[]" multiple
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @foreach ($empleados as $empleado)
                                <option value="{{ $empleado->id }}"
                                    {{ in_array($empleado->id, old('employee_ids', [])) ? 'selected' : '' }}>
                                    {{ $empleado->first_name }} {{ $empleado->last_name }} -
                                    {{ $empleado->department->name ?? 'Sin departamento' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Paso 3: Opciones Adicionales -->
                <div class="space-y-4">
                    <h4 class="text-md font-medium text-gray-900 dark:text-white">3. Opciones Adicionales</h4>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="overwrite_existing" name="overwrite_existing" value="1"
                                {{ old('overwrite_existing') ? 'checked' : '' }}
                                class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded">
                            <label for="overwrite_existing" class="ml-2 block text-sm text-gray-900 dark:text-white">
                                Sobrescribir horarios existentes en las mismas fechas
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="skip_conflicts" name="skip_conflicts" value="1"
                                {{ old('skip_conflicts', true) ? 'checked' : '' }}
                                class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded">
                            <label for="skip_conflicts" class="ml-2 block text-sm text-gray-900 dark:text-white">
                                Omitir empleados con conflictos detectados
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="publish_schedules" name="publish_schedules" value="1"
                                {{ old('publish_schedules') ? 'checked' : '' }}
                                class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded">
                            <label for="publish_schedules" class="ml-2 block text-sm text-gray-900 dark:text-white">
                                Publicar horarios automáticamente
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Resumen -->
                <div id="assignment-summary"
                    class="hidden rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                    <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Resumen de Asignación</h5>
                    <div id="summary-details" class="text-sm text-gray-600 dark:text-gray-300">
                        <!-- El resumen se cargará vía JavaScript -->
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('schedules.bulk.index') }}"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                        Previsualizar Asignación
                    </button>
                </div>
            </form>
        </div>

        <script>
            // Función para cargar detalles de la plantilla
            function loadTemplateDetails(templateId) {
                if (!templateId) {
                    document.getElementById('template-preview').classList.add('hidden');
                    return;
                }

                // Aquí iría la lógica para cargar los detalles de la plantilla vía AJAX
                // Por simplicidad, asumimos que los datos están disponibles
                const templateDetails = document.getElementById('template-details');
                templateDetails.innerHTML = `
                <div class="grid grid-cols-2 gap-4">
                    <div><strong>Nombre:</strong> Plantilla seleccionada</div>
                    <div><strong>Horario:</strong> 09:00 - 17:00</div>
                    <div><strong>Duración:</strong> 8 horas</div>
                    <div><strong>Estado:</strong> Activa</div>
                </div>
            `;
                document.getElementById('template-preview').classList.remove('hidden');
            }

            // Función para actualizar el resumen
            function updateSummary() {
                const templateId = document.getElementById('schedule_template_id').value;
                const departmentId = document.getElementById('department_id').value;
                const employeeIds = Array.from(document.getElementById('employee_ids').selectedOptions).map(option => option
                    .value);

                if (!templateId) {
                    document.getElementById('assignment-summary').classList.add('hidden');
                    return;
                }

                // Aquí iría la lógica para calcular el resumen vía AJAX
                const summaryDetails = document.getElementById('summary-details');
                summaryDetails.innerHTML = `
                <div class="grid grid-cols-2 gap-4">
                    <div><strong>Empleados objetivo:</strong> ${employeeIds.length || 'Todos los que coincidan con filtros'}</div>
                    <div><strong>Fecha de inicio:</strong> ${document.getElementById('start_date').value}</div>
                    <div><strong>Sobrescribir existentes:</strong> ${document.getElementById('overwrite_existing').checked ? 'Sí' : 'No'}</div>
                    <div><strong>Publicar automáticamente:</strong> ${document.getElementById('publish_schedules').checked ? 'Sí' : 'No'}</div>
                </div>
            `;
                document.getElementById('assignment-summary').classList.remove('hidden');
            }

            // Event listeners
            document.getElementById('schedule_template_id').addEventListener('change', function() {
                loadTemplateDetails(this.value);
                updateSummary();
            });

            document.getElementById('department_id').addEventListener('change', updateSummary);
            document.getElementById('employee_ids').addEventListener('change', updateSummary);
            document.getElementById('start_date').addEventListener('change', updateSummary);
            document.getElementById('overwrite_existing').addEventListener('change', updateSummary);
            document.getElementById('publish_schedules').addEventListener('change', updateSummary);

            // Inicializar
            loadTemplateDetails(document.getElementById('schedule_template_id').value);
            updateSummary();
        </script>
    @endsection
