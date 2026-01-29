<div x-data="{
    saveProfile() {
        console.log('Saving profile...');
    }
}">
    <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-6">
                    Personal Information
                </h4>

                <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                    <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Nombre</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ auth()->user()->empleado?->first_name ?? 'No especificado' }}</p>
                    </div>

                    <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Apellido</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ auth()->user()->empleado?->last_name ?? 'No especificado' }}</p>
                    </div>

                    <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                            Correo electrónico
                        </p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Teléfono</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ auth()->user()->empleado?->phone ?? 'No especificado' }}</p>
                    </div>

                    <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Posición</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ auth()->user()->empleado?->position ?? 'No especificado' }}</p>
                    </div>

                    <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Departamento</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ auth()->user()->empleado?->departamento?->name ?? 'No asignado' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
