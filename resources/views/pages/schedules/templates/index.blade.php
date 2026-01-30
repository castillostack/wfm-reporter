@extends('layouts.app')

@section('title', 'Plantillas de Horarios')

@section('content')
    <x-common.page-breadcrumb pageTitle="Plantillas de Horarios" :breadcrumbs="[['name' => 'Horarios', 'url' => route('schedules.index')], ['name' => 'Plantillas', 'url' => null]]" />

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <!-- Header con acciones -->
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Plantillas de Horarios
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Gestiona las plantillas reutilizables para crear horarios
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('schedules.templates.create') }}"
                    class="flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nueva Plantilla
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-50 p-4 dark:bg-green-900/20">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 dark:text-green-400">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

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

        <!-- Filtros -->
        <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
            <form method="GET" action="{{ route('schedules.templates.index') }}"
                class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                <!-- Búsqueda -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Buscar
                    </label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                        placeholder="Nombre de la plantilla..."
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
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activas</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivas</option>
                    </select>
                </div>

                <!-- Tipo -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Tipo
                    </label>
                    <select id="type" name="type"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">Todos</option>
                        <option value="standard" {{ request('type') === 'standard' ? 'selected' : '' }}>Estándar</option>
                        <option value="shift" {{ request('type') === 'shift' ? 'selected' : '' }}>Turno</option>
                        <option value="flexible" {{ request('type') === 'flexible' ? 'selected' : '' }}>Flexible</option>
                    </select>
                </div>

                <!-- Botones -->
                <div class="flex gap-2 md:col-span-2 lg:col-span-3">
                    <button type="submit"
                        class="flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Filtrar
                    </button>

                    <a href="{{ route('schedules.templates.index') }}"
                        class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Lista de plantillas -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($plantillas as $plantilla)
                <div
                    class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-white/[0.03] hover:shadow-lg transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                {{ $plantilla->name }}
                            </h4>
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $plantilla->start_time->format('H:i') }} -
                                    {{ $plantilla->end_time->format('H:i') }}</span>
                            </div>
                        </div>

                        @if ($plantilla->is_active)
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Activa
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-900/20 dark:text-gray-400">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Inactiva
                            </span>
                        @endif
                    </div>

                    <div class="space-y-3 mb-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Duración:</span>
                            <span
                                class="font-medium text-gray-900 dark:text-white">{{ $plantilla->duration_hours }}h</span>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Tipo:</span>
                            <span
                                class="font-medium text-gray-900 dark:text-white capitalize">{{ $plantilla->type }}</span>
                        </div>

                        @if ($plantilla->description)
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                {{ Str::limit($plantilla->description, 100) }}
                            </div>
                        @endif

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Usos:</span>
                            <span
                                class="font-medium text-gray-900 dark:text-white">{{ $plantilla->usage_count ?? 0 }}</span>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="flex gap-2">
                        <a href="{{ route('schedules.templates.show', $plantilla) }}"
                            class="flex-1 text-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                            Ver
                        </a>
                        <a href="{{ route('schedules.templates.edit', $plantilla) }}"
                            class="flex-1 text-center rounded-lg bg-brand-500 px-3 py-2 text-xs font-medium text-white hover:bg-brand-600">
                            Editar
                        </a>
                    </div>

                    <!-- Acciones adicionales -->
                    <div class="flex gap-2 mt-2">
                        <form action="{{ route('schedules.templates.duplicate', $plantilla) }}" method="POST"
                            class="flex-1">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="w-full text-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                Duplicar
                            </button>
                        </form>

                        @if ($plantilla->is_active)
                            <form action="{{ route('schedules.templates.deactivate', $plantilla) }}" method="POST"
                                class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-full text-center rounded-lg bg-yellow-500 px-3 py-2 text-xs font-medium text-white hover:bg-yellow-600">
                                    Desactivar
                                </button>
                            </form>
                        @else
                            <form action="{{ route('schedules.templates.activate', $plantilla) }}" method="POST"
                                class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-full text-center rounded-lg bg-green-500 px-3 py-2 text-xs font-medium text-white hover:bg-green-600">
                                    Activar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay plantillas</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comienza creando tu primera plantilla de
                            horario.</p>
                        <div class="mt-6">
                            <a href="{{ route('schedules.templates.create') }}"
                                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Crear Primera Plantilla
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if ($plantillas->hasPages())
            <div class="mt-6">
                {{ $plantillas->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection
