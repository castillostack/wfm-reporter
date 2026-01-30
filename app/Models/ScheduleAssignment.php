<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleAssignment extends Model {
    protected $fillable = [
        'schedule_template_id',
        'created_by',
        'target_filters',
        'start_date',
        'end_date',
        'schedules_created',
        'assignment_results',
    ];

    protected $casts = [
        'target_filters' => 'array',
        'assignment_results' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relaciones
    public function template(): BelongsTo {
        return $this->belongsTo(ScheduleTemplate::class, 'schedule_template_id');
    }

    public function creator(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Métodos helper
    public function getTargetEmployees() {
        $filters = $this->target_filters;

        $query = Employee::query();

        if (isset($filters['departments']) && !empty($filters['departments'])) {
            $query->whereIn('department_id', $filters['departments']);
        }

        if (isset($filters['teams']) && !empty($filters['teams'])) {
            $query->whereIn('team_id', $filters['teams']);
        }

        if (isset($filters['employees']) && !empty($filters['employees'])) {
            $query->whereIn('id', $filters['employees']);
        }

        return $query->get();
    }

    public function getDateRange() {
        return collect($this->start_date->toPeriod($this->end_date));
    }
}
