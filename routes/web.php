<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\RoleMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store'])->name('register.store');
});

// Ruta raíz - redirigir al dashboard si autenticado, sino a login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
})->name('home');

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas de perfil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.edit-password');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Rutas de asistencia
    Route::prefix('asistencia')->name('attendance.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('index');
        Route::get('/marcar', [\App\Http\Controllers\AttendanceController::class, 'markAttendance'])->name('mark');
        Route::post('/marcar', [\App\Http\Controllers\AttendanceController::class, 'markAttendance'])->name('mark.store');
        Route::get('/mi', [\App\Http\Controllers\AttendanceController::class, 'myAttendance'])->name('my');
        Route::get('/equipo', [\App\Http\Controllers\AttendanceController::class, 'teamAttendance'])->name('team');
        Route::get('/hoy', [\App\Http\Controllers\AttendanceController::class, 'todayAttendance'])->name('today');
        Route::get('/crear', [\App\Http\Controllers\AttendanceController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\AttendanceController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\AttendanceController::class, 'show'])->name('show');
    });

    // Rutas de administración (solo Analista WFM)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', \App\Http\Controllers\UserManagementController::class);
        Route::post('users/import', [\App\Http\Controllers\UserManagementController::class, 'import'])->name('users.import');
        Route::get('users/export', [\App\Http\Controllers\UserManagementController::class, 'export'])->name('users.export');
        Route::post('users/{id}/restore', [\App\Http\Controllers\UserManagementController::class, 'restore'])->name('users.restore');

        // Rutas de empleados con IDs explícitos
        Route::get('employees', [\App\Http\Controllers\EmployeeManagementController::class, 'index'])->name('employees.index');
        Route::get('employees/create', [\App\Http\Controllers\EmployeeManagementController::class, 'create'])->name('employees.create');
        Route::post('employees', [\App\Http\Controllers\EmployeeManagementController::class, 'store'])->name('employees.store');
        Route::get('employees/{employeeId}', [\App\Http\Controllers\EmployeeManagementController::class, 'show'])->name('employees.show');
        Route::get('employees/{employeeId}/edit', [\App\Http\Controllers\EmployeeManagementController::class, 'edit'])->name('employees.edit');
        Route::put('employees/{employeeId}', [\App\Http\Controllers\EmployeeManagementController::class, 'update'])->name('employees.update');
        Route::delete('employees/{employeeId}', [\App\Http\Controllers\EmployeeManagementController::class, 'destroy'])->name('employees.destroy');
        Route::post('employees/import', [\App\Http\Controllers\EmployeeManagementController::class, 'import'])->name('employees.import');
        Route::get('employees/export', [\App\Http\Controllers\EmployeeManagementController::class, 'export'])->name('employees.export');
        Route::post('employees/{employeeId}/restore', [\App\Http\Controllers\EmployeeManagementController::class, 'restore'])->name('employees.restore');

        // Rutas de departamentos
        Route::resource('departments', \App\Http\Controllers\DepartmentManagementController::class);
        Route::post('departments/{departmentId}/restore', [\App\Http\Controllers\DepartmentManagementController::class, 'restore'])->name('departments.restore');

        // Rutas de equipos
        Route::resource('teams', \App\Http\Controllers\TeamManagementController::class);
        Route::post('teams/{id}/restore', [\App\Http\Controllers\TeamManagementController::class, 'restore'])->name('teams.restore');

        // Rutas de roles
        Route::resource('roles', \App\Http\Controllers\RoleManagementController::class);

        // Rutas de permisos
        Route::resource('permissions', \App\Http\Controllers\PermissionManagementController::class);

        // Rutas de configuración
        Route::get('configuracion', [\App\Http\Controllers\ConfiguracionController::class, 'index'])->name('configuracion.index');
        Route::post('configuracion', [\App\Http\Controllers\ConfiguracionController::class, 'update'])->name('configuracion.update');
        Route::post('configuracion/clear-cache', [\App\Http\Controllers\ConfiguracionController::class, 'clearCache'])->name('configuracion.clear-cache');
        Route::post('configuracion/maintenance', [\App\Http\Controllers\ConfiguracionController::class, 'maintenance'])->name('configuracion.maintenance');
    });
});






















