<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceLog extends Model {
    protected $fillable = [
        'employee_id',
        'schedule_id',
        'check_in_time',
        'check_out_time',
        'status',
        'late_minutes',
        'worked_minutes',
        'notes',
        'created_by',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'late_minutes' => 'integer',
        'worked_minutes' => 'integer',
    ];

    // Relaciones
    public function empleado(): BelongsTo {
        return $this->belongsTo(Employee::class);
    }

    public function schedule(): BelongsTo {
        return $this->belongsTo(Schedule::class);
    }

    public function creator(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Métodos de ayuda
    public function isLate(): bool {
        return $this->status === 'late' || $this->late_minutes > 0;
    }

    public function isPresent(): bool {
        return in_array($this->status, ['present', 'late']);
    }

    public function isAbsent(): bool {
        return $this->status === 'absent';
    }

    public function isJustified(): bool {
        return $this->status === 'justified';
    }

    public function calculateWorkedMinutes(): int {
        if (!$this->check_in_time || !$this->check_out_time) {
            return 0;
        }

        return $this->check_in_time->diffInMinutes($this->check_out_time);
    }

    // Scopes
    public function scopeToday($query) {
        return $query->whereDate('check_in_time', today());
    }

    public function scopeThisWeek($query) {
        return $query->whereBetween('check_in_time', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeByStatus($query, $status) {
        return $query->where('status', $status);
    }

    public function scopeByEmployee($query, $employeeId) {
        return $query->where('employee_id', $employeeId);
    }
}
