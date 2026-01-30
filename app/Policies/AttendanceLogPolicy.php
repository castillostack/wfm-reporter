<?php

namespace App\Policies;

use App\Models\AttendanceLog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AttendanceLogPolicy {
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool {
        return $user->hasAnyPermission([
            'view_own_attendance',
            'view_team_attendance',
            'view_all_attendance'
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AttendanceLog $attendanceLog): bool {
        // El propietario siempre puede ver su asistencia
        if ($attendanceLog->empleado->user_id === $user->id) {
            return $user->hasPermissionTo('view_own_attendance');
        }

        // Analista WFM puede ver todo
        if ($user->hasRole('analista-wfm')) {
            return true;
        }

        // Director puede ver todo
        if ($user->hasRole('director-nacional')) {
            return true;
        }

        // Jefe puede ver asistencia de su departamento
        if ($user->hasRole('jefe-departamento')) {
            $departments = $user->departments ?? [];
            return $departments->contains('id', $attendanceLog->empleado->department_id);
        }

        // Coordinador puede ver asistencia de su equipo
        if ($user->hasRole('coordinador')) {
            return $attendanceLog->empleado->supervisor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool {
        return $user->hasPermissionTo('manage_attendance');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AttendanceLog $attendanceLog): bool {
        return $user->hasPermissionTo('manage_attendance');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AttendanceLog $attendanceLog): bool {
        return $user->hasPermissionTo('manage_attendance');
    }

    /**
     * Determine whether the user can mark their own attendance.
     */
    public function markOwn(User $user): bool {
        return $user->hasPermissionTo('view_own_attendance') && $user->employee;
    }

    /**
     * Determine whether the user can view team attendance.
     */
    public function viewTeam(User $user): bool {
        return $user->hasPermissionTo('view_team_attendance');
    }

    /**
     * Determine whether the user can view all attendance.
     */
    public function viewAll(User $user): bool {
        return $user->hasPermissionTo('view_all_attendance');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AttendanceLog $attendanceLog): bool {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AttendanceLog $attendanceLog): bool {
        return false;
    }
}
