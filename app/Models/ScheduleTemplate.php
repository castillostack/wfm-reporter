<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleTemplate extends Model {
   use HasFactory;

   protected $fillable = [
      'name',
      'start_time',
      'end_time',
      'lunch_duration_minutes',
      'break_duration_minutes',
      'is_active',
   ];

   protected function casts(): array {
      return [
         'start_time' => 'datetime:H:i',
         'end_time' => 'datetime:H:i',
         'is_active' => 'boolean',
         'lunch_duration_minutes' => 'integer',
         'break_duration_minutes' => 'integer',
         'total_duration_minutes' => 'integer',
      ];
   }

   // Relaciones
   public function creador() {
      return $this->belongsTo(User::class, 'created_by');
   }

   public function horarios() {
      return $this->hasMany(Schedule::class, 'schedule_template_id');
   }

   public function asignacionesMasivas() {
      return $this->hasMany(ScheduleAssignment::class);
   }

   // Métodos helper
   public function getWorkDurationAttribute() {
      if (!$this->start_time || !$this->end_time) {
         return 0;
      }

      $start = \Carbon\Carbon::createFromFormat('H:i', $this->start_time->format('H:i'));
      $end = \Carbon\Carbon::createFromFormat('H:i', $this->end_time->format('H:i'));

      return $start->diffInMinutes($end);
   }

   public function getEffectiveWorkDurationAttribute() {
      return $this->work_duration - ($this->lunch_duration_minutes ?? 0) - ($this->break_duration_minutes ?? 0);
   }

   public function getFormattedDurationAttribute() {
      $hours = floor($this->effective_work_duration / 60);
      $minutes = $this->effective_work_duration % 60;

      if ($hours > 0) {
         return "{$hours}h {$minutes}min";
      }

      return "{$minutes}min";
   }

   public function getLunchStartTimeAttribute() {
      if (!$this->start_time) {
         return null;
      }

      // Asumimos que el almuerzo comienza 4 horas después del inicio
      $start = \Carbon\Carbon::createFromFormat('H:i', $this->start_time->format('H:i'));
      return $start->addHours(4)->format('H:i');
   }

   public function getLunchEndTimeAttribute() {
      if (!$this->lunch_start_time) {
         return null;
      }

      $lunchStart = \Carbon\Carbon::createFromFormat('H:i', $this->lunch_start_time);
      return $lunchStart->addMinutes($this->lunch_duration_minutes ?? 60)->format('H:i');
   }

   public function isUsed() {
      return $this->horarios()->exists();
   }
}
