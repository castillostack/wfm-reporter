<?php

declare(strict_types=1);

namespace App\Actions\Horarios;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\Schedule;
use Carbon\Carbon;

final class ValidarHorarioAction {
    public function handle(array $datos): array {
        $errores = [];

        // Validar formato de horas
        if (!$this->horaValida($datos['scheduled_start'])) {
            $errores[] = 'Hora de inicio inválida';
        }

        if (!$this->horaValida($datos['scheduled_end'])) {
            $errores[] = 'Hora de fin inválida';
        }

        // Validar que la hora de fin sea posterior a la de inicio
        if (isset($datos['scheduled_start'], $datos['scheduled_end'])) {
            $start = Carbon::parse($datos['scheduled_start']);
            $end = Carbon::parse($datos['scheduled_end']);

            if ($end->lte($start)) {
                $errores[] = 'La hora de fin debe ser posterior a la hora de inicio';
            }
        }

        // Validar que no haya solapamiento con otros horarios del mismo empleado
        if (isset($datos['employee_id'], $datos['date'], $datos['scheduled_start'], $datos['scheduled_end'])) {
            $conflicto = Schedule::where('employee_id', $datos['employee_id'])
                ->where('date', $datos['date'])
                ->where('id', '!=', $datos['id'] ?? null) // Excluir el horario actual si es una actualización
                ->where(function ($q) use ($datos) {
                    $q->where(function ($sq) use ($datos) {
                        // Caso 1: El nuevo horario inicia dentro de un horario existente
                        $sq->where('scheduled_start', '<=', $datos['scheduled_start'])
                            ->where('scheduled_end', '>', $datos['scheduled_start']);
                    })
                        ->orWhere(function ($sq) use ($datos) {
                            // Caso 2: El nuevo horario termina dentro de un horario existente
                            $sq->where('scheduled_start', '<', $datos['scheduled_end'])
                                ->where('scheduled_end', '>=', $datos['scheduled_end']);
                        })
                        ->orWhere(function ($sq) use ($datos) {
                            // Caso 3: El nuevo horario engloba completamente un horario existente
                            $sq->where('scheduled_start', '>=', $datos['scheduled_start'])
                                ->where('scheduled_end', '<=', $datos['scheduled_end']);
                        });
                })->exists();

            if ($conflicto) {
                $errores[] = 'Conflicto de horario detectado con otro turno programado';
            }
        }

        // Validar horas laborales (ejemplo: 6:00 - 22:00)
        if (!$this->dentroHorasLaborales($datos['scheduled_start'] ?? null, $datos['scheduled_end'] ?? null)) {
            $errores[] = 'El horario está fuera de las horas laborales permitidas (06:00 - 22:00)';
        }

        // Validar conflicto con permisos aprobados
        if (isset($datos['employee_id'], $datos['date'])) {
            $permiso = Leave::where('employee_id', $datos['employee_id'])
                ->where('status', 'approved')
                ->whereDate('start_time', '<=', $datos['date'])
                ->whereDate('end_time', '>=', $datos['date'])
                ->exists();

            if ($permiso) {
                $errores[] = 'Conflicto con permiso aprobado para esta fecha';
            }
        }

        // Validar duración máxima del turno (ejemplo: máximo 12 horas)
        if (isset($datos['scheduled_start'], $datos['scheduled_end'])) {
            $start = Carbon::parse($datos['scheduled_start']);
            $end = Carbon::parse($datos['scheduled_end']);
            $duracion = $start->diffInHours($end);

            if ($duracion > 12) {
                $errores[] = 'La duración del turno no puede exceder las 12 horas';
            }
        }

        return $errores;
    }

    private function horaValida(?string $hora): bool {
        if (!$hora) {
            return false;
        }

        try {
            Carbon::parse($hora);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function dentroHorasLaborales(?string $inicio, ?string $fin): bool {
        if (!$inicio || !$fin) {
            return true; // Si no hay horas, no validamos
        }

        $horaInicioLaboral = Carbon::createFromTime(6, 0, 0); // 06:00
        $horaFinLaboral = Carbon::createFromTime(22, 0, 0);   // 22:00

        $start = Carbon::parse($inicio)->setDate(2000, 1, 1); // Normalizar fecha para comparación
        $end = Carbon::parse($fin)->setDate(2000, 1, 1);

        return $start->gte($horaInicioLaboral) && $end->lte($horaFinLaboral);
    }
}
