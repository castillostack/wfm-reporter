<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'employee_number',
        'cedula',
        'gender',
        'birth_date',
        'phone',
        'hire_date',
        'salary',
        'position',
        'department_id',
        'supervisor_id',
    ];

    protected function casts(): array {
        return [
            'birth_date' => 'date',
            'hire_date' => 'date',
            'salary' => 'decimal:2',
        ];
    }

    public function usuario(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function departamento(): BelongsTo {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function supervisor(): BelongsTo {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function horarios(): HasMany {
        return $this->hasMany(Schedule::class, 'employee_id');
    }

    public function permisos(): HasMany {
        return $this->hasMany(Leave::class, 'employee_id');
    }

    public function cambiosTurnoSolicitados(): HasMany {
        return $this->hasMany(ShiftSwap::class, 'requester_id');
    }

    public function cambiosTurnoRecibidos(): HasMany {
        return $this->hasMany(ShiftSwap::class, 'recipient_id');
    }
}
