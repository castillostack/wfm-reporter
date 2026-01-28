<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShiftSwap extends Model {
   use HasFactory;

   protected $fillable = [
      'requester_id',
      'recipient_id',
      'requester_schedule_id',
      'recipient_schedule_id',
      'status',
      'approved_by',
      'approved_at',
   ];

   protected function casts(): array {
      return [
         'approved_at' => 'datetime',
      ];
   }

   public function solicitante(): BelongsTo {
      return $this->belongsTo(Employee::class, 'requester_id');
   }

   public function receptor(): BelongsTo {
      return $this->belongsTo(Employee::class, 'recipient_id');
   }

   public function horarioSolicitante(): BelongsTo {
      return $this->belongsTo(Schedule::class, 'requester_schedule_id');
   }

   public function horarioReceptor(): BelongsTo {
      return $this->belongsTo(Schedule::class, 'recipient_schedule_id');
   }

   public function aprobadoPor(): BelongsTo {
      return $this->belongsTo(User::class, 'approved_by');
   }
}
