<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model {
   use HasFactory;

   protected $fillable = [
      'employee_id',
      'type',
      'start_time',
      'end_time',
      'reason',
      'attachment_path',
      'status',
      'approved_by',
   ];

   protected function casts(): array {
      return [
         'start_time' => 'datetime',
         'end_time' => 'datetime',
      ];
   }

   public function empleado(): BelongsTo {
      return $this->belongsTo(Employee::class, 'employee_id');
   }

   public function aprobadoPor(): BelongsTo {
      return $this->belongsTo(User::class, 'approved_by');
   }
}
