<?php

declare(strict_types=1);

namespace App\Actions\Horarios;

use App\Models\Employee;
use App\Models\Schedule;
use App\Models\ScheduleTemplate;
use App\Models\ScheduleAssignment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class AsignarHorariosMasivosAction {
    public function handle(array $datos): array {
        $empleados = $this->obtenerEmpleados($datos['target_filters']);
        $fechas = $this->generarFechas($datos['start_date'], $datos['end_date']);
        $plantilla = ScheduleTemplate::findOrFail($datos['schedule_template_id']);

        $creados = 0;
        $conflictos = [];
        $errores = [];

        // Crear registro de asignación masiva
        $asignacion = ScheduleAssignment::create([
            'schedule_template_id' => $plantilla->id,
            'created_by' => auth()->id(),
            'target_filters' => $datos['target_filters'],
            'start_date' => $datos['start_date'],
            'end_date' => $datos['end_date'],
        ]);

        DB::transaction(function () use ($empleados, $fechas, $plantilla, &$creados, &$conflictos, &$errores, $asignacion) {
            foreach ($empleados as $empleado) {
                foreach ($fechas as $fecha) {
                    try {
                        // Verificar conflictos antes de crear
                        $datosHorario = [
                            'employee_id' => $empleado->id,
                            'date' => $fecha,
                            'scheduled_start' => $fecha . ' ' . $plantilla->start_time,
                            'scheduled_end' => $fecha . ' ' . $plantilla->end_time,
                        ];

                        $validacion = app(ValidarHorarioAction::class)->handle($datosHorario);

                        if (!empty($validacion)) {
                            $conflictos[] = [
                                'empleado' => $empleado->id,
                                'fecha' => $fecha,
                                'errores' => $validacion
                            ];
                            continue;
                        }

                        // Crear el horario
                        Schedule::create(array_merge($datosHorario, [
                            'schedule_template_id' => $plantilla->id,
                            'status' => 'scheduled',
                            'is_published' => false,
                            'created_by' => auth()->id(),
                        ]));

                        $creados++;

                    } catch (\Exception $e) {
                        $errores[] = [
                            'empleado' => $empleado->id,
                            'fecha' => $fecha,
                            'error' => $e->getMessage()
                        ];
                    }
                }
            }
        });

        // Actualizar estadísticas de la asignación
        $asignacion->update([
            'schedules_created' => $creados,
            'assignment_results' => [
                'creados' => $creados,
                'conflictos' => count($conflictos),
                'errores' => count($errores),
                'detalles_conflictos' => $conflictos,
                'detalles_errores' => $errores,
            ]
        ]);

        return [
            'creados' => $creados,
            'conflictos' => $conflictos,
            'errores' => $errores,
            'asignacion_id' => $asignacion->id,
        ];
    }

    public function obtenerEmpleados(array $filtros): Collection {
        $query = Employee::query();

        if (isset($filtros['departments']) && !empty($filtros['departments'])) {
            $query->whereIn('department_id', $filtros['departments']);
        }

        if (isset($filtros['teams']) && !empty($filtros['teams'])) {
            $query->whereIn('team_id', $filtros['teams']);
        }

        if (isset($filtros['employees']) && !empty($filtros['employees'])) {
            $query->whereIn('id', $filtros['employees']);
        }

        return $query->get();
    }

    public function generarFechas(string $fechaInicio, string $fechaFin): array {
        $fechas = [];
        $inicio = \Carbon\Carbon::parse($fechaInicio);
        $fin = \Carbon\Carbon::parse($fechaFin);

        while ($inicio->lte($fin)) {
            $fechas[] = $inicio->format('Y-m-d');
            $inicio->addDay();
        }

        return $fechas;
    }
}
