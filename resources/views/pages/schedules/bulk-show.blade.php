@extends('layouts.app')

@section('title', 'Detalles de Asignación Masiva')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Detalles de Asignación Masiva</h1>
            <a href="{{ route('schedules.bulk.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Volver al Listado
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold">Asignación #{{ $assignment_id }}</h2>
            </div>

            <div class="p-6">
                <p class="text-gray-600">Esta funcionalidad está en desarrollo. Las asignaciones masivas no se persisten como
                    entidades individuales.</p>
            </div>
        </div>
    </div>
@endsection
