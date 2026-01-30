@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Detalle de Asistencia</h1>
            <a href="{{ route('attendance.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Volver al Listado
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold">Registro de {{ $attendanceLog->empleado->first_name }}
                    {{ $attendanceLog->empleado->last_name }}</h2>
            </div>

            <div class="p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Empleado</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $attendanceLog->empleado->first_name }} {{ $attendanceLog->empleado->last_name }}
                            <br>
                            <span class="text-gray-500">{{ $attendanceLog->empleado->employee_number }}</span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Fecha</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendanceLog->check_in_time->format('d/m/Y') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Hora de Entrada</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendanceLog->check_in_time->format('H:i') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Hora de Salida</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $attendanceLog->check_out_time ? $attendanceLog->check_out_time->format('H:i') : '-' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Estado</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if ($attendanceLog->status === 'present') bg-green-100 text-green-800
                            @elseif($attendanceLog->status === 'late') bg-yellow-100 text-yellow-800
                            @elseif($attendanceLog->status === 'absent') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($attendanceLog->status) }}
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Minutos de Retraso</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendanceLog->late_minutes ?? 0 }} min</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Horas Trabajadas</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if ($attendanceLog->worked_minutes)
                                {{ floor($attendanceLog->worked_minutes / 60) }}h
                                {{ $attendanceLog->worked_minutes % 60 }}min
                            @else
                                -
                            @endif
                        </dd>
                    </div>

                    @if ($attendanceLog->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Notas</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $attendanceLog->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
@endsection
