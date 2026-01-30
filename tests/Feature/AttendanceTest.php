<?php

use App\Models\User;
use App\Models\Employee;
use App\Models\AttendanceLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Crear permisos necesarios para los tests
    Permission::firstOrCreate(['name' => 'view_all_attendance']);
    Permission::firstOrCreate(['name' => 'view_own_attendance']);

    // Crear roles necesarios para los tests
    $analystRole = Role::firstOrCreate(['name' => 'analista-wfm']);
    $operatorRole = Role::firstOrCreate(['name' => 'operador']);

    // Asignar permisos a los roles
    $analystRole->syncPermissions(['view_all_attendance']);
    $operatorRole->syncPermissions(['view_own_attendance']);
});

test('analista wfm puede acceder al listado de asistencia', function () {
    $analyst = User::factory()->create();
    $analyst->assignRole('analista-wfm');

    $response = $this->actingAs($analyst)->get('/asistencia');

    $response->assertStatus(200);
});

test('usuario sin rol analista wfm no puede acceder al listado de asistencia', function () {
    $user = User::factory()->create();
    $user->assignRole('operador');

    $response = $this->actingAs($user)->get('/asistencia');

    $response->assertStatus(403);
});

test('operador puede acceder a su propia asistencia', function () {
    $user = User::factory()->create();
    $employee = Employee::factory()->create(['user_id' => $user->id]);
    $user->assignRole('operador');

    // Verificar que el empleado se creó correctamente
    expect($user->empleado)->not->toBeNull();
    expect($user->empleado->id)->toBe($employee->id);

    $response = $this->actingAs($user)->get('/asistencia/mi');

    $response->assertStatus(200);
});

test('usuario no autenticado no puede acceder a asistencia', function () {
    $response = $this->get('/asistencia');

    $response->assertStatus(302); // Redirect to login
});
