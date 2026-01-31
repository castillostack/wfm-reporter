@extends('layouts.app')

@section('title', 'Gestionar Usuarios del Rol: ' . $role->name)

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
                                <h1 class="text-2xl font-bold text-gray-900">Gestionar Usuarios del Rol</h1>
                                <p class="text-gray-600">Rol: {{ $role->name }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.roles.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Volver a Roles
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Asignar/Quitar Usuarios</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Usa los botones de acción para mover usuarios entre listas. Los cambios se guardan automáticamente.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.roles.users.update', $role) }}" id="manageUsersForm">
                    @csrf
                    @method('PUT')

                    <!-- Hidden input to indicate action type -->
                    <input type="hidden" name="action" id="actionInput" value="save">

                    <div class="px-6 py-4">
                        <!-- Dual Listbox Component -->
                        <div class="flex space-x-4">
                            <!-- Available Users Column -->
                            <div class="flex-1">
                                <div class="mb-3">
                                    <label for="availableUsers" class="block text-sm font-medium text-gray-700 mb-2">
                                        Usuarios Disponibles
                                        <span class="text-xs text-gray-500">({{ count($users) - count($roleUsers) }})</span>
                                    </label>
                                    <input type="text" id="availableSearch" placeholder="Buscar usuario..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                </div>
                                <div class="border border-gray-300 rounded-md h-96 overflow-y-auto bg-white">
                                    <select id="availableUsers" multiple size="20"
                                        class="w-full h-full p-2 text-sm focus:outline-none">
                                        @foreach ($users as $user)
                                            @if (!in_array($user->id, $roleUsers))
                                                @php
                                                    $currentRole = $user->roles->first();
                                                @endphp
                                                @if (count($user->roles) > 0)
                                                    @continue
                                                @endif
                                                <option value="{{ $user->id }}">
                                                    {{ $user->name }} ({{ $user->email }})
                                                    @if ($currentRole)
                                                        [Actual: {{ $currentRole->name }}]
                                                    @endif
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Control Buttons -->
                            <div class="flex flex-col justify-center space-y-2 px-2">
                                <button type="button" id="assignSelected"
                                    class="p-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                    title="Asignar seleccionados">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                <button type="button" id="removeSelected"
                                    class="p-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                    title="Remover seleccionados">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <div class="border-t border-gray-200 my-2"></div>
                                <button type="button" id="assignAll"
                                    class="p-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                    title="Asignar todos">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                <button type="button" id="removeAll"
                                    class="p-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                    title="Remover todos">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Assigned Users Column -->
                            <div class="flex-1">
                                <div class="mb-3">
                                    <label for="assignedUsers" class="block text-sm font-medium text-gray-700 mb-2">
                                        Usuarios Asignados
                                        <span class="text-xs text-gray-500">({{ count($roleUsers) }})</span>
                                    </label>
                                </div>
                                <div class="border border-gray-300 rounded-md h-96 overflow-y-auto bg-white">
                                    <select id="assignedUsers" multiple size="20"
                                        class="w-full h-full p-2 text-sm focus:outline-none">
                                        @foreach ($users as $user)
                                            @if (in_array($user->id, $roleUsers))
                                                <option value="{{ $user->id }}">
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden input to store assigned users -->
                        <input type="hidden" name="users" id="assignedUsersInput"
                            value="{{ implode(',', $roleUsers) }}">
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                        <a href="{{ route('admin.roles.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const availableUsers = document.getElementById('availableUsers');
            const assignedUsers = document.getElementById('assignedUsers');
            const availableSearch = document.getElementById('availableSearch');
            const assignedUsersInput = document.getElementById('assignedUsersInput');

            const assignSelectedBtn = document.getElementById('assignSelected');
            const removeSelectedBtn = document.getElementById('removeSelected');
            const assignAllBtn = document.getElementById('assignAll');
            const removeAllBtn = document.getElementById('removeAll');

            console.log('Manage Users Script Loaded');

            // Update button states
            function updateButtonStates() {
                const availableSelected = Array.from(availableUsers.selectedOptions).length > 0;
                const assignedSelected = Array.from(assignedUsers.selectedOptions).length > 0;
                const availableCount = availableUsers.options.length;
                const assignedCount = assignedUsers.options.length;

                assignSelectedBtn.disabled = !availableSelected;
                removeSelectedBtn.disabled = !assignedSelected;
                assignAllBtn.disabled = availableCount === 0;
                removeAllBtn.disabled = assignedCount === 0;
            }

            // Update counters
            function updateCounters() {
                const availableCount = availableUsers.options.length;
                const assignedCount = assignedUsers.options.length;

                document.querySelector('label[for="availableUsers"]').innerHTML =
                    `Usuarios Disponibles <span class="text-xs text-gray-500">(${availableCount})</span>`;
                document.querySelector('label[for="assignedUsers"]').innerHTML =
                    `Usuarios Asignados <span class="text-xs text-gray-500">(${assignedCount})</span>`;
            }

            // Update hidden input with assigned user IDs
            function updateHiddenInput() {
                const assignedIds = Array.from(assignedUsers.options).map(option => option.value);
                assignedUsersInput.value = assignedIds.join(',');
            }

            // Submit form after action
            function submitForm(actionType) {
                document.getElementById('actionInput').value = actionType;
                document.getElementById('manageUsersForm').submit();
            }

            // Move selected options from source to target and submit
            function moveOptionsAndSubmit(source, target, actionType) {
                const selectedOptions = Array.from(source.selectedOptions);
                if (selectedOptions.length === 0) return; // Don't submit if nothing selected

                selectedOptions.forEach(option => {
                    target.appendChild(option);
                });
                updateCounters();
                updateHiddenInput();
                submitForm(actionType);
            }

            // Move all options from source to target and submit
            function moveAllOptionsAndSubmit(source, target, actionType) {
                const allOptions = Array.from(source.options);
                if (allOptions.length === 0) return; // Don't submit if nothing to move

                allOptions.forEach(option => {
                    target.appendChild(option);
                });
                updateCounters();
                updateHiddenInput();
                submitForm(actionType);
            }

            // Filter available users based on search
            function filterAvailableUsers() {
                const searchTerm = availableSearch.value.toLowerCase();
                Array.from(availableUsers.options).forEach(option => {
                    const text = option.text.toLowerCase();
                    option.style.display = text.includes(searchTerm) ? 'list-item' : 'none';
                });
            }

            // Event listeners
            assignSelectedBtn.addEventListener('click', (e) => {
                e.preventDefault();
                moveOptionsAndSubmit(availableUsers, assignedUsers, 'assign_selected');
            });
            removeSelectedBtn.addEventListener('click', (e) => {
                e.preventDefault();
                moveOptionsAndSubmit(assignedUsers, availableUsers, 'remove_selected');
            });
            assignAllBtn.addEventListener('click', (e) => {
                e.preventDefault();
                moveAllOptionsAndSubmit(availableUsers, assignedUsers, 'assign_all');
            });
            removeAllBtn.addEventListener('click', (e) => {
                e.preventDefault();
                moveAllOptionsAndSubmit(assignedUsers, availableUsers, 'remove_all');
            });

            availableSearch.addEventListener('input', filterAvailableUsers);

            // Update states on selection change
            availableUsers.addEventListener('change', updateButtonStates);
            assignedUsers.addEventListener('change', updateButtonStates);

            // Double-click to move single items
            availableUsers.addEventListener('dblclick', (e) => {
                e.preventDefault();
                moveOptionsAndSubmit(availableUsers, assignedUsers, 'assign_selected');
            });
            assignedUsers.addEventListener('dblclick', (e) => {
                e.preventDefault();
                moveOptionsAndSubmit(assignedUsers, availableUsers, 'remove_selected');
            });

            // Initialize states
            updateButtonStates();
            updateCounters();
        });
    </script>
@endpush
