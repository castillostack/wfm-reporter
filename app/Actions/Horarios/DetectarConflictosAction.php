<?php

declare(strict_types=1);

namespace App\Actions\Horarios;

use App\Models\Leave;
use App\Models\Schedule;
use App\Models\ScheduleConflict;
use Illuminate\Support\Collection;

final class DetectarConflictosAction {
   public function handle(Collection $horarios): Collection {
      return $horarios->map(function ($horario) {
         $conflictos = [];

         // Conflicto de doble turno
         $dobleTurno = Schedule::where('employee_id', $horario['employee_id'])
            ->where('date', $horario['date'])
            ->where('id', '!=', $horario['id'] ?? null)
            ->exists();

         if ($dobleTurno) {
            $conflictos[] = [
               'tipo' => 'double_booking',
               'severidad' => 'high',
               'mensaje' => 'Doble turno detectado para el mismo día'
            ];
         }

         // Conflicto de solapamiento de tiempo
         $solapamiento = Schedule::where('employee_id', $horario['employee_id'])
            ->where('date', $horario['date'])
            ->where('id', '!=', $horario['id'] ?? null)
            ->where(function ($q) use ($horario) {
               $q->where(function ($sq) use ($horario) {
                  $sq->where('scheduled_start', '<=', $horario['scheduled_start'])
                     ->where('scheduled_end', '>', $horario['scheduled_start']);
               })
                  ->orWhere(function ($sq) use ($horario) {
                     $sq->where('scheduled_start', '<', $horario['scheduled_end'])
                        ->where('scheduled_end', '>=', $horario['scheduled_end']);
                  });
            })->exists();

         if ($solapamiento) {
            $conflictos[] = [
               'tipo' => 'overlap',
               'severidad' => 'critical',
               'mensaje' => 'Solapamiento de horario con otro turno'
            ];
         }

         // Conflicto con permisos
         $permiso = Leave::where('employee_id', $horario['employee_id'])
            ->where('status', 'approved')
            ->whereDate('start_time', '<=', $horario['date'])
            ->whereDate('end_time', '>=', $horario['date'])
            ->exists();

         if ($permiso) {
            $conflictos[] = [
               'tipo' => 'leave_conflict',
               'severidad' => 'critical',
               'mensaje' => 'Conflicto con permiso aprobado'
            ];
         }

         // Validar horas laborales
         if (!$this->dentroHorasLaborales($horario['scheduled_start'], $horario['scheduled_end'])) {
            $conflictos[] = [
               'tipo' => 'outside_business_hours',
               'severidad' => 'medium',
               'mensaje' => 'Horario fuera de horas laborales (06:00 - 22:00)'
            ];
         }

         // Registrar conflictos en la base de datos si hay alguno crítico
         $conflictosCriticos = collect($conflictos)->where('severidad', 'critical');
         if ($conflictosCriticos->isNotEmpty()) {
            $this->registrarConflicto($horario, $conflictosCriticos->first());
         }

         return array_merge($horario, ['conflictos' => $conflictos]);
      });
   }

   private function dentroHorasLaborales(string $inicio, string $fin): bool {
      $horaInicioLaboral = \Carbon\Carbon::createFromTime(6, 0, 0);
      $horaFinLaboral = \Carbon\Carbon::createFromTime(22, 0, 0);

      $start = \Carbon\Carbon::parse($inicio)->setDate(2000, 1, 1);
      $end = \Carbon\Carbon::parse($fin)->setDate(2000, 1, 1);

      return $start->gte($horaInicioLaboral) && $end->lte($horaFinLaboral);
   }

   private function registrarConflicto(array $horario, array $conflicto): void {
      ScheduleConflict::create([
         'employee_id' => $horario['employee_id'],
         'date' => $horario['date'],
         'conflict_type' => $conflicto['tipo'],
         'details' => [
            'mensaje' => $conflicto['mensaje'],
            'horario_programado' => [
               'inicio' => $horario['scheduled_start'],
               'fin' => $horario['scheduled_end']
            ]
         ],
         'severity' => $conflicto['severidad'],
      ]);
   }
}
