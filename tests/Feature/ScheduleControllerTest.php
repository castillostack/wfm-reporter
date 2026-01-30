<?php

use App\Models\User;
use App\Models\Schedule;
use App\Models\Employee;
use Spatie\Permission\Models\Permission;

test('it can access schedules index with proper permissions', function () {
    // Crear permisos necesarios
    Permission::firstOrCreate(['name' => 'view_all_attendance']);

    // Crear usuario con permisos
    $user = User::factory()->create();
    $user->givePermissionTo('view_all_attendance');

    // Crear algunos datos de prueba
    Employee::factory()->create();
    Schedule::factory()->create();

    // Hacer la petición
    $response = $this->actingAs($user)->get('/horarios');

    // Verificar que la respuesta sea exitosa
    expect($response->status())->toBe(200);
    expect($response->getData()->name)->toBe('pages.schedules.index');
});

test('it denies access without proper permissions', function () {
    // Crear usuario sin permisos
    $user = User::factory()->create();

    // Hacer la petición
    $response = $this->actingAs($user)->get('/horarios');

    // Verificar que sea redirigido (403 Forbidden)
    expect($response->status())->toBe(403);
});

test('it redirects unauthenticated users', function () {
    // Hacer la petición sin autenticación
    $response = $this->get('/horarios');

    // Verificar que sea redirigido al login
    expect($response->status())->toBe(302);
    expect($response->getTargetUrl())->toContain('/login');
});
