@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Crear Registro de Asistencia</h1>
            <a href="{{ route('attendance.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Volver al Listado
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('attendance.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="employee_id" class="block text-sm font-medium text-gray-700">Empleado</label>
                    <select name="employee_id" id="employee_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                        <option value="">Seleccionar empleado...</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}
                                ({{ $employee->employee_number }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="check_in_time" class="block text-sm font-medium text-gray-700">Hora de Entrada</label>
                    <input type="datetime-local" name="check_in_time" id="check_in_time"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                </div>

                <div class="mb-4">
                    <label for="check_out_time" class="block text-sm font-medium text-gray-700">Hora de Salida</label>
                    <input type="datetime-local" name="check_out_time" id="check_out_time"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select name="status" id="status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                        <option value="present">Presente</option>
                        <option value="late">Tarde</option>
                        <option value="absent">Ausente</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notas</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Crear Registro
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
