<div x-data="{
    saveProfile() {
        console.log('Saving profile...');
    }
}">
    <div class="mb-6 rounded-2xl border border-gray-200 p-5 lg:p-6 dark:border-gray-800">
        <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
            <div class="flex w-full flex-col items-center gap-6 xl:flex-row">
                <div
                    class="h-20 w-20 overflow-hidden rounded-full border border-gray-200 dark:border-gray-800 bg-brand-500 text-white flex items-center justify-center text-2xl font-bold">
                    @php
                        $nameParts = explode(' ', auth()->user()->name);
                        $initials = strtoupper($nameParts[1][0] . $nameParts[0][0] ?? '');
                    @endphp
                    {{ $initials }}
                </div>
                <div class="order-3 xl:order-2">
                    <h4 class="mb-2 text-center text-lg font-semibold text-gray-800 xl:text-left dark:text-white/90">
                        {{ auth()->user()->name }}
                    </h4>
                    <div class="flex flex-col items-center gap-1 text-center xl:flex-row xl:gap-3 xl:text-left">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ auth()->user()->empleado?->position ?? 'Usuario' }}
                        </p>
                        <div class="hidden h-3.5 w-px bg-gray-300 xl:block dark:bg-gray-700"></div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ auth()->user()->empleado?->departamento?->name ?? 'Sin departamento' }}
                        </p>
                    </div>
                </div>
                <div class="order-2 flex grow items-center gap-2 xl:order-3 xl:justify-end">
                    <!-- Botones de acción para WFM -->
                </div>
            </div>

        </div>
    </div>

</div>
