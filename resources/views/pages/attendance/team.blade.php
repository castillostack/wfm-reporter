@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Asistencia de Mi Equipo</h1>
            <a href="{{ route('attendance.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Volver al Listado
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold">Miembros del Equipo</h2>
            </div>

            <div class="p-6">
                @if ($teamMembers->count() > 0)
                    @foreach ($teamMembers as $member)
                        <div class="mb-6">
                            <h3 class="text-md font-medium text-gray-900 mb-3">{{ $member->first_name }}
                                {{ $member->last_name }}</h3>

                            @php
                                $memberAttendance = $attendanceLogs->where('employee_id', $member->id);
                            @endphp

                            @if ($memberAttendance->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Fecha</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Entrada</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Salida</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Estado</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Tarde</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($memberAttendance as $log)
                                                <tr>
                                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $log->check_in_time->format('d/m/Y') }}
                                                    </td>
                                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $log->check_in_time->format('H:i') }}
                                                    </td>
                                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $log->check_out_time ? $log->check_out_time->format('H:i') : '-' }}
                                                    </td>
                                                    <td class="px-4 py-2 whitespace-nowrap">
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if ($log->status === 'present') bg-green-100 text-green-800
                                                @elseif($log->status === 'late') bg-yellow-100 text-yellow-800
                                                @elseif($log->status === 'absent') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                            {{ ucfirst($log->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $log->late_minutes ?? 0 }} min
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No hay registros de asistencia para este miembro.</p>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">No tienes miembros en tu equipo.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
