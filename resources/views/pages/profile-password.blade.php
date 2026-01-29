@extends('layouts.app')

@section('title', 'Cambiar Contraseña')

@section('content')
    <x-common.page-breadcrumb pageTitle="Cambiar Contraseña" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <!-- Formulario de cambio de contraseña -->
        <div class="lg:col-span-8">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">
                    Cambiar Contraseña
                </h3>

                <form action="{{ route('profile.update-password') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('POST')

                    <!-- Contraseña actual -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Contraseña Actual *
                        </label>
                        <input type="password" id="current_password" name="current_password"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('current_password') border-red-500 @enderror"
                            required>
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nueva contraseña -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nueva Contraseña *
                        </label>
                        <input type="password" id="password" name="password"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('password') border-red-500 @enderror"
                            required>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            La contraseña debe tener al menos 8 caracteres.
                        </p>
                    </div>

                    <!-- Confirmar nueva contraseña -->
                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Confirmar Nueva Contraseña *
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('password_confirmation') border-red-500 @enderror"
                            required>
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Indicador de fortaleza de contraseña -->
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-800 dark:text-white/90 mb-2">
                            Requisitos de la contraseña:
                        </h4>
                        <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                            <li id="length-check" class="flex items-center">
                                <span class="w-2 h-2 rounded-full bg-gray-300 mr-2"></span>
                                Al menos 8 caracteres
                            </li>
                            <li id="different-check" class="flex items-center">
                                <span class="w-2 h-2 rounded-full bg-gray-300 mr-2"></span>
                                Diferente a la contraseña actual
                            </li>
                            <li id="match-check" class="flex items-center">
                                <span class="w-2 h-2 rounded-full bg-gray-300 mr-2"></span>
                                Las contraseñas coinciden
                            </li>
                        </ul>
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
                            Cambiar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Información de seguridad -->
        <div class="lg:col-span-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">
                    Información de Seguridad
                </h4>

                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                Contraseña Segura
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Usa una combinación de letras, números y símbolos.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                Verificación Requerida
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Confirma tu identidad con la contraseña actual.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                Cambio Irreversible
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Una vez cambiada, no podrás recuperar la contraseña anterior.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Último cambio -->
            <div
                class="mt-6 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">
                    Historial
                </h4>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Último cambio</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ auth()->user()->updated_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Estado</span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            Activa
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para validación en tiempo real -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentPassword = document.getElementById('current_password');
            const newPassword = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');

            const lengthCheck = document.getElementById('length-check');
            const differentCheck = document.getElementById('different-check');
            const matchCheck = document.getElementById('match-check');

            function updateValidation() {
                const currentVal = currentPassword.value;
                const newVal = newPassword.value;
                const confirmVal = confirmPassword.value;

                // Longitud mínima
                if (newVal.length >= 8) {
                    lengthCheck.querySelector('span').className = 'w-2 h-2 rounded-full bg-green-500 mr-2';
                } else {
                    lengthCheck.querySelector('span').className = 'w-2 h-2 rounded-full bg-gray-300 mr-2';
                }

                // Diferente a la actual
                if (newVal && currentVal && newVal !== currentVal) {
                    differentCheck.querySelector('span').className = 'w-2 h-2 rounded-full bg-green-500 mr-2';
                } else if (newVal && currentVal) {
                    differentCheck.querySelector('span').className = 'w-2 h-2 rounded-full bg-red-500 mr-2';
                } else {
                    differentCheck.querySelector('span').className = 'w-2 h-2 rounded-full bg-gray-300 mr-2';
                }

                // Coinciden
                if (newVal && confirmVal && newVal === confirmVal) {
                    matchCheck.querySelector('span').className = 'w-2 h-2 rounded-full bg-green-500 mr-2';
                } else if (newVal && confirmVal) {
                    matchCheck.querySelector('span').className = 'w-2 h-2 rounded-full bg-red-500 mr-2';
                } else {
                    matchCheck.querySelector('span').className = 'w-2 h-2 rounded-full bg-gray-300 mr-2';
                }
            }

            currentPassword.addEventListener('input', updateValidation);
            newPassword.addEventListener('input', updateValidation);
            confirmPassword.addEventListener('input', updateValidation);
        });
    </script>
@endsection
