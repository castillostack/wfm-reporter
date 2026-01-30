<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleConflict extends Model {
    protected $fillable = [
        'employee_id',
        'date',
        'conflict_type',
        'details',
        'severity',
        'resolved',
        'resolved_at',
        'resolved_by',
    ];

    protected $casts = [
        'date' => 'date',
        'details' => 'array',
        'resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    // Relaciones
    public function employee(): BelongsTo {
        return $this->belongsTo(Employee::class);
    }

    public function resolver(): BelongsTo {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // Scopes
    public function scopeUnresolved($query) {
        return $query->where('resolved', false);
    }

    public function scopeBySeverity($query, $severity) {
        return $query->where('severity', $severity);
    }

    public function scopeByType($query, $type) {
        return $query->where('conflict_type', $type);
    }

    // Métodos helper
    public function resolve(User $user = null) {
        $this->update([
            'resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => $user?->id,
        ]);
    }

    public function getSeverityColor() {
        return match ($this->severity) {
            'low' => 'green',
            'medium' => 'yellow',
            'high' => 'orange',
            'critical' => 'red',
            default => 'gray',
        };
    }

    public function getTypeLabel() {
        return match ($this->conflict_type) {
            'overlap' => 'Solapamiento de horarios',
            'double_booking' => 'Doble turno',
            'outside_business_hours' => 'Fuera de horas laborales',
            'leave_conflict' => 'Conflicto con permiso',
            default => ucfirst(str_replace('_', ' ', $this->conflict_type)),
        };
    }
}
