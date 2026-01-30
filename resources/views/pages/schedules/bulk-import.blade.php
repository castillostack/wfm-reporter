@extends('layouts.app')

@section('title', 'Importar Horarios desde CSV')

@section('content')
    <x-common.page-breadcrumb pageTitle="Importar Horarios desde CSV" :breadcrumbs="[
        ['name' => 'Horarios', 'url' => route('schedules.index')],
        ['name' => 'Asignaciones Masivas', 'url' => route('schedules.bulk.index')],
        ['name' => 'Importar CSV', 'url' => null],
    ]" />

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <!-- Header -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Importar Horarios desde CSV
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Sube un archivo CSV para importar horarios masivamente
            </p>
        </div>

        <!-- Formulario de Importación -->
        <form action="{{ route('schedules.bulk.import') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <!-- Archivo CSV -->
                <div>
                    <label for="csv_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Archivo CSV
                    </label>
                    <div class="mt-1">
                        <input type="file" name="csv_file" id="csv_file" accept=".csv,.txt"
                            class="block w-full text-sm text-gray-500 dark:text-gray-400
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-lg file:border-0
                                      file:text-sm file:font-medium
                                      file:bg-brand-50 file:text-brand-700
                                      dark:file:bg-brand-900/20 dark:file:text-brand-400
                                      hover:file:bg-brand-100 dark:hover:file:bg-brand-900/30"
                            required>
                    </div>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Selecciona un archivo CSV con los horarios a importar. Tamaño máximo: 10MB.
                    </p>
                </div>

                <!-- Información del formato -->
                <div class="rounded-lg bg-blue-50 p-4 dark:bg-blue-900/20">
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
                                Formato del archivo CSV
                            </h3>
                            <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                <p>El archivo debe contener las siguientes columnas:</p>
                                <ul class="mt-2 list-disc list-inside space-y-1">
                                    <li><code>employee_id</code> - ID del empleado</li>
                                    <li><code>date</code> - Fecha del horario (YYYY-MM-DD)</li>
                                    <li><code>start_time</code> - Hora de inicio (HH:MM)</li>
                                    <li><code>end_time</code> - Hora de fin (HH:MM)</li>
                                    <li><code>schedule_template_id</code> - ID de la plantilla (opcional)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('schedules.bulk.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-brand-500 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Importar Horarios
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
