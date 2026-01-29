<div x-data="{
    saveProfile() {
        console.log('Saving profile...');
    }
}">
    <div class="p-5 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-6">Información Laboral</h4>

                <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                    <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Número de Empleado</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ auth()->user()->empleado?->employee_number ?? 'No asignado' }}</p>
                    </div>

                    <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Cédula</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ auth()->user()->empleado?->cedula ?? 'No especificada' }}</p>
                    </div>

                    <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Fecha de Contratación</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ auth()->user()->empleado?->hire_date?->format('d/m/Y') ?? 'No especificada' }}</p>
                    </div>

                    <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Fecha de Nacimiento</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ auth()->user()->empleado?->birth_date?->format('d/m/Y') ?? 'No especificada' }}</p>
                    </div>

                    <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Género</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ auth()->user()->empleado?->gender ?? 'No especificado' }}</p>
                    </div>

                    <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Supervisor</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ auth()->user()->empleado?->supervisor?->name ?? 'No asignado' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
