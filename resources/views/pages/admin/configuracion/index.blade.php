@extends('layouts.app')

@section('title', 'Configuración del Sistema')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                            </a>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Configuración del Sistema</h1>
                                <p class="text-gray-600">Administra la configuración general de la aplicación</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Configuración General -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Configuración General
                            </h3>
                            <p class="mt-1 text-sm text-gray-600">
                                Configuraciones básicas del sistema
                            </p>
                        </div>

                        <form action="{{ route('admin.configuracion.update') }}" method="POST" class="space-y-6">
                            @csrf

                            <div class="px-6 py-4">
                                <!-- Nombre de la Aplicación -->
                                <div class="mb-6">
                                    <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre de la Aplicación <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="app_name" name="app_name"
                                        value="{{ old('app_name', $configuraciones['app_name']) }}"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('app_name') border-red-300 @enderror"
                                        required>
                                    @error('app_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Zona Horaria -->
                                <div class="mb-6">
                                    <label for="app_timezone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Zona Horaria <span class="text-red-500">*</span>
                                    </label>
                                    <select id="app_timezone" name="app_timezone"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('app_timezone') border-red-300 @enderror"
                                        required>
                                        <option value="">Seleccionar zona horaria</option>
                                        @foreach (timezone_identifiers_list() as $timezone)
                                            <option value="{{ $timezone }}"
                                                {{ old('app_timezone', $configuraciones['app_timezone']) == $timezone ? 'selected' : '' }}>
                                                {{ $timezone }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('app_timezone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Driver de Correo -->
                                <div class="mb-6">
                                    <label for="mail_driver" class="block text-sm font-medium text-gray-700 mb-2">
                                        Driver de Correo <span class="text-red-500">*</span>
                                    </label>
                                    <select id="mail_driver" name="mail_driver"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('mail_driver') border-red-300 @enderror"
                                        required>
                                        <option value="smtp"
                                            {{ old('mail_driver', $configuraciones['mail_driver']) == 'smtp' ? 'selected' : '' }}>
                                            SMTP</option>
                                        <option value="mailgun"
                                            {{ old('mail_driver', $configuraciones['mail_driver']) == 'mailgun' ? 'selected' : '' }}>
                                            Mailgun</option>
                                        <option value="ses"
                                            {{ old('mail_driver', $configuraciones['mail_driver']) == 'ses' ? 'selected' : '' }}>
                                            Amazon SES</option>
                                        <option value="postmark"
                                            {{ old('mail_driver', $configuraciones['mail_driver']) == 'postmark' ? 'selected' : '' }}>
                                            Postmark</option>
                                        <option value="sendmail"
                                            {{ old('mail_driver', $configuraciones['mail_driver']) == 'sendmail' ? 'selected' : '' }}>
                                            Sendmail</option>
                                    </select>
                                    @error('mail_driver')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Driver de Caché -->
                                <div class="mb-6">
                                    <label for="cache_driver" class="block text-sm font-medium text-gray-700 mb-2">
                                        Driver de Caché <span class="text-red-500">*</span>
                                    </label>
                                    <select id="cache_driver" name="cache_driver"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('cache_driver') border-red-300 @enderror"
                                        required>
                                        <option value="file"
                                            {{ old('cache_driver', $configuraciones['cache_driver']) == 'file' ? 'selected' : '' }}>
                                            Archivo</option>
                                        <option value="database"
                                            {{ old('cache_driver', $configuraciones['cache_driver']) == 'database' ? 'selected' : '' }}>
                                            Base de Datos</option>
                                        <option value="redis"
                                            {{ old('cache_driver', $configuraciones['cache_driver']) == 'redis' ? 'selected' : '' }}>
                                            Redis</option>
                                        <option value="memcached"
                                            {{ old('cache_driver', $configuraciones['cache_driver']) == 'memcached' ? 'selected' : '' }}>
                                            Memcached</option>
                                    </select>
                                    @error('cache_driver')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Driver de Sesiones -->
                                <div class="mb-6">
                                    <label for="session_driver" class="block text-sm font-medium text-gray-700 mb-2">
                                        Driver de Sesiones <span class="text-red-500">*</span>
                                    </label>
                                    <select id="session_driver" name="session_driver"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('session_driver') border-red-300 @enderror"
                                        required>
                                        <option value="file"
                                            {{ old('session_driver', $configuraciones['session_driver']) == 'file' ? 'selected' : '' }}>
                                            Archivo</option>
                                        <option value="cookie"
                                            {{ old('session_driver', $configuraciones['session_driver']) == 'cookie' ? 'selected' : '' }}>
                                            Cookie</option>
                                        <option value="database"
                                            {{ old('session_driver', $configuraciones['session_driver']) == 'database' ? 'selected' : '' }}>
                                            Base de Datos</option>
                                        <option value="redis"
                                            {{ old('session_driver', $configuraciones['session_driver']) == 'redis' ? 'selected' : '' }}>
                                            Redis</option>
                                        <option value="memcached"
                                            {{ old('session_driver', $configuraciones['session_driver']) == 'memcached' ? 'selected' : '' }}>
                                            Memcached</option>
                                    </select>
                                    @error('session_driver')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Conexión de Base de Datos -->
                                <div class="mb-6">
                                    <label for="database_connection" class="block text-sm font-medium text-gray-700 mb-2">
                                        Conexión de Base de Datos <span class="text-red-500">*</span>
                                    </label>
                                    <select id="database_connection" name="database_connection"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('database_connection') border-red-300 @enderror"
                                        required>
                                        <option value="pgsql"
                                            {{ old('database_connection', $configuraciones['database_connection']) == 'pgsql' ? 'selected' : '' }}>
                                            PostgreSQL</option>
                                        <option value="mysql"
                                            {{ old('database_connection', $configuraciones['database_connection']) == 'mysql' ? 'selected' : '' }}>
                                            MySQL</option>
                                        <option value="sqlite"
                                            {{ old('database_connection', $configuraciones['database_connection']) == 'sqlite' ? 'selected' : '' }}>
                                            SQLite</option>
                                        <option value="sqlsrv"
                                            {{ old('database_connection', $configuraciones['database_connection']) == 'sqlsrv' ? 'selected' : '' }}>
                                            SQL Server</option>
                                    </select>
                                    @error('database_connection')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Guardar Configuración
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Panel de Acciones -->
                <div>
                    <!-- Limpiar Caché -->
                    <div class="bg-white shadow rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Mantenimiento
                            </h3>
                            <p class="mt-1 text-sm text-gray-600">
                                Operaciones de mantenimiento del sistema
                            </p>
                        </div>
                        <div class="px-6 py-4 space-y-4">
                            <form action="{{ route('admin.configuracion.clear-cache') }}" method="POST"
                                class="space-y-3">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                    Limpiar Cachés
                                </button>
                            </form>

                            <form action="{{ route('admin.configuracion.maintenance') }}" method="POST"
                                class="space-y-3">
                                @csrf
                                <select name="command"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="optimize">Optimizar Aplicación</option>
                                    <option value="clear-compiled">Limpiar Archivos Compilados</option>
                                    <option value="key-generate">Generar Clave de App</option>
                                    <option value="migrate-status">Verificar Migraciones</option>
                                </select>
                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Ejecutar Comando
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Información del Sistema -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Información del Sistema
                            </h3>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Versión de Laravel</dt>
                                    <dd class="text-sm text-gray-900">{{ app()->version() }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Versión de PHP</dt>
                                    <dd class="text-sm text-gray-900">{{ PHP_VERSION }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Entorno</dt>
                                    <dd class="text-sm text-gray-900">{{ config('app.env') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Base de Datos</dt>
                                    <dd class="text-sm text-gray-900">{{ config('database.default') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Última actualización</dt>
                                    <dd class="text-sm text-gray-900">{{ now()->format('d/m/Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-submit para comandos de mantenimiento (opcional)
        document.querySelectorAll('form[action*="maintenance"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                const button = this.querySelector('button[type="submit"]');
                const originalText = button.innerHTML;
                button.innerHTML =
                    '<svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Ejecutando...';
                button.disabled = true;
            });
        });
    </script>
@endsection
