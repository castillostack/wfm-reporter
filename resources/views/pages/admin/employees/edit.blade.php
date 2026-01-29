@extends('layouts.app')

@section('title', 'Editar Empleado')

@section('content')
    <x-common.page-breadcrumb pageTitle="Editar Empleado" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <!-- Formulario de edición -->
        <div class="lg:col-span-8">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">
                    Información del Empleado
                </h3>

                <form action="{{ route('admin.employees.update', $empleado->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Información básica -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="employee_number"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Número de Empleado *
                            </label>
                            <input type="text" id="employee_number" name="employee_number"
                                value="{{ old('employee_number', $empleado->employee_number) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('employee_number') border-red-500 @enderror"
                                required>
                            @error('employee_number')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Usuario Asociado
                            </label>
                            <select id="user_id" name="user_id"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800 @error('user_id') border-red-500 @enderror">
                                <option value="">Sin usuario asociado</option>
                                @php
                                    $currentUserId = $empleado->user_id ?? null;
                                    $availableUsers = \App\Models\User::where(function ($query) use ($currentUserId) {
                                        $query->whereDoesntHave('empleado');
                                        if ($currentUserId) {
                                            $query->orWhere('id', $currentUserId);
                                        }
                                    })->get();
                                @endphp
                                @foreach ($availableUsers as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('user_id', $currentUserId) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Nombre y apellido -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nombre *
                            </label>
                            <input type="text" id="first_name" name="first_name"
                                value="{{ old('first_name', $empleado->first_name) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('first_name') border-red-500 @enderror"
                                required>
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Apellido *
                            </label>
                            <input type="text" id="last_name" name="last_name"
                                value="{{ old('last_name', $empleado->last_name) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('last_name') border-red-500 @enderror"
                                required>
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email y teléfono -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Correo Electrónico *
                            </label>
                            <input type="email" id="email" name="email"
                                value="{{ old('email', $empleado->email) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('email') border-red-500 @enderror"
                                required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Teléfono
                            </label>
                            <input type="tel" id="phone" name="phone"
                                value="{{ old('phone', $empleado->phone) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Departamento y supervisor -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="department_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Departamento *
                            </label>
                            <select id="department_id" name="department_id"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800 @error('department_id') border-red-500 @enderror"
                                required>
                                <option value="">Seleccionar departamento</option>
                                @foreach ($departamentos as $departamento)
                                    <option value="{{ $departamento->id }}"
                                        {{ old('department_id', $empleado->department_id) == $departamento->id ? 'selected' : '' }}>
                                        {{ $departamento->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="supervisor_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Supervisor
                            </label>
                            <select id="supervisor_id" name="supervisor_id"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800 @error('supervisor_id') border-red-500 @enderror">
                                <option value="">Sin supervisor</option>
                                @foreach ($supervisores as $supervisor)
                                    <option value="{{ $supervisor->id }}"
                                        {{ old('supervisor_id', $empleado->supervisor_id) == $supervisor->id ? 'selected' : '' }}>
                                        {{ $supervisor->first_name }} {{ $supervisor->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supervisor_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Cargo y fecha de contratación -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Cargo *
                            </label>
                            <input type="text" id="position" name="position"
                                value="{{ old('position', $empleado->position) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('position') border-red-500 @enderror"
                                required>
                            @error('position')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="hire_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Fecha de Contratación
                            </label>
                            <input type="date" id="hire_date" name="hire_date"
                                value="{{ old('hire_date', $empleado->hire_date?->format('Y-m-d')) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800 @error('hire_date') border-red-500 @enderror">
                            @error('hire_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Información adicional -->
                    <div class="border-t border-gray-200 pt-6 dark:border-gray-700">
                        <h4 class="mb-4 text-lg font-medium text-gray-800 dark:text-white/90">
                            Información Adicional
                        </h4>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="salary"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Salario
                                </label>
                                <input type="number" id="salary" name="salary"
                                    value="{{ old('salary', $empleado->salary) }}" step="0.01" min="0"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('salary') border-red-500 @enderror">
                                @error('salary')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="address"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Dirección
                                </label>
                                <input type="text" id="address" name="address"
                                    value="{{ old('address', $empleado->address) }}"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('address') border-red-500 @enderror">
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Contacto de emergencia -->
                        <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="emergency_contact_name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Contacto de Emergencia
                                </label>
                                <input type="text" id="emergency_contact_name" name="emergency_contact_name"
                                    value="{{ old('emergency_contact_name', $empleado->emergency_contact_name) }}"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('emergency_contact_name') border-red-500 @enderror">
                                @error('emergency_contact_name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="emergency_contact_phone"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Teléfono de Emergencia
                                </label>
                                <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone"
                                    value="{{ old('emergency_contact_phone', $empleado->emergency_contact_phone) }}"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('emergency_contact_phone') border-red-500 @enderror">
                                @error('emergency_contact_phone')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div
                        class="flex flex-col gap-3 pt-6 border-t border-gray-200 dark:border-gray-700 sm:flex-row sm:justify-end">
                        <a href="{{ route('admin.employees.show', $empleado) }}"
                            class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                            Ver Detalles
                        </a>
                        <a href="{{ route('admin.employees.index') }}"
                            class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 sm:w-auto">
                            Actualizar Empleado
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Vista previa del empleado -->
        <div class="lg:col-span-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">
                    Vista Previa
                </h4>

                <div class="flex flex-col items-center text-center">
                    <!-- Avatar -->
                    <div
                        class="mb-4 h-20 w-20 rounded-full bg-brand-500 flex items-center justify-center text-white font-medium text-2xl">
                        {{ substr($empleado->first_name, 0, 1) . substr($empleado->last_name, 0, 1) }}
                    </div>

                    <!-- Información -->
                    <h5 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white/90">
                        {{ $empleado->first_name }} {{ $empleado->last_name }}
                    </h5>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                        {{ $empleado->position }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        #{{ $empleado->employee_number }}
                    </p>

                    <!-- Estado -->
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $empleado->deleted_at ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                        {{ $empleado->deleted_at ? 'Inactivo' : 'Activo' }}
                    </span>
                </div>

                <!-- Información adicional -->
                <div class="mt-6 space-y-3 border-t border-gray-200 pt-4 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Email</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $empleado->email }}</span>
                    </div>
                    @if ($empleado->phone)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Teléfono</span>
                            <span
                                class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $empleado->phone }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Departamento</span>
                        <span
                            class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $empleado->departamento?->name ?? 'Sin asignar' }}</span>
                    </div>
                    @if ($empleado->supervisor)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Supervisor</span>
                            <span
                                class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $empleado->supervisor->first_name }}
                                {{ $empleado->supervisor->last_name }}</span>
                        </div>
                    @endif
                    @if ($empleado->hire_date)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Fecha Contratación</span>
                            <span
                                class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $empleado->hire_date->format('d/m/Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
