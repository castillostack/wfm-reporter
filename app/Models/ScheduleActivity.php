<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleActivity extends Model {
   use HasFactory;

   protected $fillable = [
      'schedule_id',
      'activity_type',
      'start_time',
      'end_time',
      'duration_minutes',
   ];

   protected function casts(): array {
      return [
         'start_time' => 'datetime',
         'end_time' => 'datetime',
      ];
   }

   public function horario(): BelongsTo {
      return $this->belongsTo(Schedule::class, 'schedule_id');
   }
}
