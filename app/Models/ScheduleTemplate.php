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
      ];
   }
}
