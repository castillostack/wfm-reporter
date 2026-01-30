<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model {
   use HasFactory;

   protected $fillable = [
      'employee_id',
      'date',
      'scheduled_start',
      'scheduled_end',
      'status',
      'is_published',
   ];

   protected function casts(): array {
      return [
         'date' => 'date',
         'scheduled_start' => 'datetime',
         'scheduled_end' => 'datetime',
         'is_published' => 'boolean',
      ];
   }

   public function empleado(): BelongsTo {
      return $this->belongsTo(Employee::class, 'employee_id');
   }

   public function actividades(): HasMany {
      return $this->hasMany(ScheduleActivity::class, 'schedule_id');
   }

   public function cambiosTurnoSolicitados(): HasMany {
      return $this->hasMany(ShiftSwap::class, 'requester_schedule_id');
   }

   public function cambiosTurnoRecibidos(): HasMany {
      return $this->hasMany(ShiftSwap::class, 'recipient_schedule_id');
   }

   public function registroAsistencia(): HasMany {
      return $this->hasMany(AttendanceLog::class, 'schedule_id');
   }
}
