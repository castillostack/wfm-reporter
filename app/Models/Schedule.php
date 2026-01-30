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
      'schedule_template_id',
      'date',
      'scheduled_start',
      'scheduled_end',
      'actual_start',
      'actual_end',
      'status',
      'is_published',
      'notes',
      'created_by',
      'published_at',
      'compliance_score',
   ];

   protected function casts(): array {
      return [
         'date' => 'date',
         'scheduled_start' => 'datetime',
         'scheduled_end' => 'datetime',
         'actual_start' => 'datetime',
         'actual_end' => 'datetime',
         'is_published' => 'boolean',
         'published_at' => 'datetime',
         'compliance_score' => 'decimal:2',
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

   public function plantilla(): BelongsTo {
      return $this->belongsTo(ScheduleTemplate::class, 'schedule_template_id');
   }

   public function creador(): BelongsTo {
      return $this->belongsTo(User::class, 'created_by');
   }

   public function conflictos(): HasMany {
      return $this->hasMany(ScheduleConflict::class, 'employee_id', 'employee_id')
         ->where('date', $this->date);
   }

   // Métodos helper
   public function getScheduledDurationAttribute() {
      if (!$this->scheduled_start || !$this->scheduled_end) {
         return 0;
      }
      return $this->scheduled_start->diffInMinutes($this->scheduled_end);
   }

   public function getActualDurationAttribute() {
      if (!$this->actual_start || !$this->actual_end) {
         return 0;
      }
      return $this->actual_start->diffInMinutes($this->actual_end);
   }

   public function getCompliancePercentageAttribute() {
      if (!$this->compliance_score) {
         return 0;
      }
      return round($this->compliance_score * 100, 1);
   }

   public function hasConflicts() {
      return $this->conflictos()->unresolved()->exists();
   }

   public function publish() {
      $this->update([
         'is_published' => true,
         'published_at' => now(),
      ]);
   }

   public function unpublish() {
      $this->update([
         'is_published' => false,
         'published_at' => null,
      ]);
   }

   /**
    * Resolve route model binding with validation
    */
   public function resolveRouteBinding($value, $field = null) {
      // Validate that the value is numeric to prevent SQL errors
      if (!is_numeric($value)) {
         return null;
      }

      return $this->where($field ?? $this->getRouteKeyName(), $value)->first();
   }
}
