@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
    <x-common.page-breadcrumb pageTitle="Editar Perfil" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <!-- Formulario de edición -->
        <div class="lg:col-span-8">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">
                    Información Personal
                </h3>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('POST')

                    <!-- Nombre completo -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nombre Completo *
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $usuario->name) }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('name') border-red-500 @enderror"
                            required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Correo Electrónico *
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $usuario->email) }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('email') border-red-500 @enderror"
                            required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nombre y Apellido -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nombre
                            </label>
                            <input type="text" id="first_name" name="first_name"
                                value="{{ old('first_name', $usuario->empleado?->first_name) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('first_name') border-red-500 @enderror">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Apellido
                            </label>
                            <input type="text" id="last_name" name="last_name"
                                value="{{ old('last_name', $usuario->empleado?->last_name) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('last_name') border-red-500 @enderror">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Teléfono y Cargo -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Teléfono
                            </label>
                            <input type="tel" id="phone" name="phone"
                                value="{{ old('phone', $usuario->empleado?->phone) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Cargo
                            </label>
                            <input type="text" id="position" name="position"
                                value="{{ old('position', $usuario->empleado?->position) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('position') border-red-500 @enderror">
                            @error('position')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div
                        class="flex flex-col gap-3 pt-6 border-t border-gray-200 dark:border-gray-700 sm:flex-row sm:justify-end">
                        <a href="{{ route('profile.show') }}"
                            class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 sm:w-auto">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Información del perfil actual -->
        <div class="lg:col-span-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">
                    Vista Previa
                </h4>

                <div class="flex flex-col items-center text-center">
                    <!-- Avatar -->
                    <div
                        class="mb-4 h-20 w-20 overflow-hidden rounded-full border border-gray-200 bg-brand-500 text-white flex items-center justify-center text-2xl font-bold dark:border-gray-800">
                        @php
                            $nameParts = explode(' ', $usuario->name);
                            $initials = strtoupper(
                                substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''),
                            );
                        @endphp
                        {{ $initials }}
                    </div>

                    <!-- Información -->
                    <h5 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white/90">
                        {{ $usuario->name }}
                    </h5>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                        {{ $usuario->empleado?->position ?? 'Usuario' }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $usuario->email }}
                    </p>

                    @if ($usuario->empleado)
                        <div class="mt-4 w-full border-t border-gray-200 pt-4 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Departamento</p>
                            <p class="text-sm text-gray-800 dark:text-white/90">
                                {{ $usuario->empleado->departamento?->name ?? 'Sin asignar' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información adicional -->
            <div
                class="mt-6 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">
                    Información del Sistema
                </h4>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">ID de Usuario</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $usuario->id }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Fecha de Registro</span>
                        <span
                            class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $usuario->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Última Actualización</span>
                        <span
                            class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $usuario->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
