@extends('layouts.app')

@section('title', 'Editar Rol')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.roles.index') }}" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                            </a>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Editar Rol</h1>
                                <p class="text-gray-600">Modifica la información del rol: {{ $role->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white shadow rounded-lg">
                <form method="POST" action="{{ route('admin.roles.update', $role) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Información del Rol
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Modifica los detalles del rol.
                        </p>
                    </div>

                    <div class="px-6 py-4 space-y-6">
                        <!-- Nombre -->
                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nombre del Rol <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('name') border-red-500 dark:border-red-500 @enderror"
                                placeholder="ej: analista-wfm, coordinador" required>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Solo letras minúsculas y guiones. Ejemplo: analista-wfm
                            </p>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label for="description"
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Descripción
                            </label>
                            <textarea name="description" id="description" rows="3"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('description') border-red-500 dark:border-red-500 @enderror"
                                placeholder="Describe las responsabilidades de este rol...">{{ old('description', $role->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Permisos -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Permisos
                            </label>
                            <p class="text-sm text-gray-500 mb-4">
                                Selecciona los permisos que tendrá este rol.
                            </p>

                            @php
                                $permissions = \Spatie\Permission\Models\Permission::all()->groupBy(function (
                                    $permission,
                                ) {
                                    return explode('.', $permission->name)[0] ?? 'general';
                                });
                            @endphp

                            @foreach ($permissions as $group => $groupPermissions)
                                <div class="mb-6">
                                    <h4 class="text-md font-medium text-gray-900 mb-3 capitalize">{{ $group }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                        @foreach ($groupPermissions as $permission)
                                            <div class="flex items-center">
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                    id="permission-{{ $permission->id }}"
                                                    {{ $role->hasPermissionTo($permission) ? 'checked' : '' }}
                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                <label for="permission-{{ $permission->id }}"
                                                    class="ml-2 block text-sm text-gray-900">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            @error('permissions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gestión de Usuarios -->
                        <hr>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Gestión de Usuarios
                            </label>
                            <p class="text-sm text-gray-500 mb-4">
                                Asigna o remueve usuarios de este rol.
                            </p>

                            <div x-data="transferList()" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Usuarios Disponibles -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Usuarios Disponibles</h4>
                                    <input type="text" x-model="searchAvailable" placeholder="Buscar usuario..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm mb-3">
                                    <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-md">
                                        <div class="p-2">
                                            <template x-for="user in filteredAvailable" :key="user.id">
                                                <div class="flex items-center p-2 hover:bg-gray-100 rounded">
                                                    <input type="checkbox" :id="'available-' + user.id"
                                                        :value="user.id" x-model="selectedAvailable" class="mr-2">
                                                    <label :for="'available-' + user.id" class="text-sm text-gray-700">
                                                        @{{ user.name }} (@{{ user.email }})
                                                    </label>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- Controles -->
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <button type="button" @click="moveToAssigned()"
                                        class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700"
                                        :disabled="selectedAvailable.length === 0">
                                        &gt;
                                    </button>
                                    <button type="button" @click="moveAllToAssigned()"
                                        class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">
                                        &gt;&gt;
                                    </button>
                                    <button type="button" @click="moveToAvailable()"
                                        class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700"
                                        :disabled="selectedAssigned.length === 0">
                                        &lt;
                                    </button>
                                    <button type="button" @click="moveAllToAvailable()"
                                        class="px-3 py-1 bg-gray-600 text-white rounded text-sm hover:bg-gray-700">
                                        &lt;&lt;
                                    </button>
                                </div>

                                <!-- Usuarios Asignados -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Usuarios Asignados</h4>
                                    <input type="text" x-model="searchAssigned" placeholder="Buscar usuario..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm mb-3">
                                    <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-md">
                                        <div class="p-2">
                                            @foreach ($usuariosAsignados as $user)
                                                <div class="flex items-center p-2 hover:bg-gray-100 rounded">
                                                    <input type="checkbox" id="assigned-{{ $user->id }}"
                                                        value="{{ $user->id }}" class="mr-2">
                                                    <label for="assigned-{{ $user->id }}"
                                                        class="text-sm text-gray-700">
                                                        {{ $user->name }} ({{ $user->email }})
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Campo oculto para usuarios asignados -->
                            <input type="hidden" name="assigned_users" x-model="assignedUsersJson">
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                        <a href="{{ route('admin.roles.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Actualizar Rol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function transferList() {
                return {
                    availableUsers: @json($usuariosDisponibles),
                    assignedUsers: @json($usuariosAsignados),
                    selectedAvailable: [],
                    selectedAssigned: [],
                    searchAvailable: '',
                    searchAssigned: '',

                    get filteredAvailable() {
                        return this.availableUsers.filter(user =>
                            user.name.toLowerCase().includes(this.searchAvailable.toLowerCase()) ||
                            user.email.toLowerCase().includes(this.searchAvailable.toLowerCase())
                        );
                    },

                    get filteredAssigned() {
                        return this.assignedUsers.filter(user =>
                            user.name.toLowerCase().includes(this.searchAssigned.toLowerCase()) ||
                            user.email.toLowerCase().includes(this.searchAssigned.toLowerCase())
                        );
                    },

                    get assignedUsersJson() {
                        return JSON.stringify(this.assignedUsers.map(u => u.id));
                    },

                    moveToAssigned() {
                        this.selectedAvailable.forEach(id => {
                            const user = this.availableUsers.find(u => u.id == id);
                            if (user) {
                                this.assignedUsers.push(user);
                                this.availableUsers = this.availableUsers.filter(u => u.id != id);
                            }
                        });
                        this.selectedAvailable = [];
                    },

                    moveToAvailable() {
                        this.selectedAssigned.forEach(id => {
                            const user = this.assignedUsers.find(u => u.id == id);
                            if (user) {
                                this.availableUsers.push(user);
                                this.assignedUsers = this.assignedUsers.filter(u => u.id != id);
                            }
                        });
                        this.selectedAssigned = [];
                    },

                    moveAllToAssigned() {
                        this.assignedUsers.push(...this.availableUsers);
                        this.availableUsers = [];
                    },

                    moveAllToAvailable() {
                        this.availableUsers.push(...this.assignedUsers);
                        this.assignedUsers = [];
                    }
                }
            }
        </script>
    @endsection
