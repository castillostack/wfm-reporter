<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'leader_id',
        'department_id',
    ];

    public function lider(): BelongsTo {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function departamento(): BelongsTo {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function empleados(): HasMany {
        return $this->hasMany(Employee::class, 'team_id');
    }
}
