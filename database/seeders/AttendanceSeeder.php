<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $employees = \App\Models\Employee::all();
        $schedules = \App\Models\Schedule::where('date', '>=', now()->subDays(7))->get();

        // Crear registros de asistencia para los últimos 7 días
        foreach ($employees as $employee) {
            for ($i = 0; $i < 7; $i++) {
                $date = now()->subDays($i);
                $schedule = $schedules->where('employee_id', $employee->id)
                    ->where('date', $date->toDateString())
                    ->first();

                if ($schedule) {
                    // Simular diferentes escenarios de asistencia
                    $attendanceType = $this->getRandomAttendanceType();

                    $checkInTime = $schedule->scheduled_start->copy();

                    switch ($attendanceType) {
                        case 'present':
                            // A tiempo o con poco retraso
                            $lateMinutes = rand(0, 5);
                            $checkInTime = $checkInTime->addMinutes($lateMinutes);
                            $status = $lateMinutes > 0 ? 'late' : 'present';
                            break;

                        case 'late':
                            // Tarde significativa
                            $lateMinutes = rand(10, 45);
                            $checkInTime = $checkInTime->addMinutes($lateMinutes);
                            $status = 'late';
                            break;

                        case 'absent':
                            // Ausente
                            $status = 'absent';
                            $lateMinutes = 0;
                            break;

                        case 'justified':
                            // Justificado (vacaciones, permiso)
                            $status = 'justified';
                            $lateMinutes = 0;
                            break;
                    }

                    $checkOutTime = $status !== 'absent' ?
                        $schedule->scheduled_end->copy()->addMinutes(rand(-10, 10)) : null;

                    $workedMinutes = $checkOutTime ?
                        $checkInTime->diffInMinutes($checkOutTime) : null;

                    \App\Models\AttendanceLog::create([
                        'employee_id' => $employee->id,
                        'schedule_id' => $schedule->id,
                        'check_in_time' => $checkInTime,
                        'check_out_time' => $checkOutTime,
                        'status' => $status,
                        'late_minutes' => $lateMinutes ?? 0,
                        'worked_minutes' => $workedMinutes,
                        'notes' => $this->getRandomNote($status),
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                }
            }
        }
    }

    private function getRandomAttendanceType(): string {
        $types = [
            'present' => 70,   // 70% llegan a tiempo
            'late' => 15,      // 15% llegan tarde
            'absent' => 10,    // 10% ausentes
            'justified' => 5,  // 5% justificados
        ];

        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($types as $type => $percentage) {
            $cumulative += $percentage;
            if ($rand <= $cumulative) {
                return $type;
            }
        }

        return 'present';
    }

    private function getRandomNote(string $status): ?string {
        $notes = [
            'late' => [
                'Tráfico intenso',
                'Retraso en transporte público',
                'Problemas familiares',
                'Desperté tarde',
            ],
            'absent' => [
                'Enfermedad',
                'Accidente',
                'Problemas familiares urgentes',
            ],
            'justified' => [
                'Vacaciones programadas',
                'Permiso médico',
                'Día libre aprobado',
                'Capacitación externa',
            ],
        ];

        if (isset($notes[$status])) {
            return $notes[$status][array_rand($notes[$status])];
        }

        return null;
    }
}
