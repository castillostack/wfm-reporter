<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('analista wfm puede acceder a configuracion', function () {
    // Crear usuario con rol analista-wfm
    $user = User::factory()->create();
    $analistaRole = Role::firstOrCreate(['name' => 'analista-wfm']);
    $user->assignRole($analistaRole);

    $response = $this->actingAs($user)->get(route('admin.configuracion.index'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.admin.configuracion.index');
    $response->assertViewHas('configuraciones');
});

test('usuario sin rol analista wfm no puede acceder a configuracion', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('admin.configuracion.index'));

    $response->assertStatus(403);
});

test('usuario no autenticado no puede acceder a configuracion', function () {
    $response = $this->get(route('admin.configuracion.index'));

    $response->assertRedirect(route('login'));
});

test('analista wfm puede actualizar configuracion', function () {
    $user = User::factory()->create();
    $analistaRole = Role::firstOrCreate(['name' => 'analista-wfm']);
    $user->assignRole($analistaRole);

    $configData = [
        'app_name' => 'WFM Reporter Test',
        'app_timezone' => 'America/Mexico_City',
        'mail_driver' => 'smtp',
        'cache_driver' => 'file',
        'session_driver' => 'file',
        'database_connection' => 'pgsql',
    ];

    $response = $this->actingAs($user)->post(route('admin.configuracion.update'), $configData);

    $response->assertRedirect(route('admin.configuracion.index'));
    $response->assertSessionHas('success');
});

test('validacion falla con datos invalidos en configuracion', function () {
    $user = User::factory()->create();
    $analistaRole = Role::firstOrCreate(['name' => 'analista-wfm']);
    $user->assignRole($analistaRole);

    $invalidData = [
        'app_name' => '', // requerido
        'app_timezone' => 'invalid_timezone',
        'mail_driver' => 'invalid_driver',
    ];

    $response = $this->actingAs($user)->post(route('admin.configuracion.update'), $invalidData);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['app_name', 'app_timezone', 'mail_driver']);
});

test('analista wfm puede limpiar cache', function () {
    $user = User::factory()->create();
    $analistaRole = Role::firstOrCreate(['name' => 'analista-wfm']);
    $user->assignRole($analistaRole);

    $response = $this->actingAs($user)->post(route('admin.configuracion.clear-cache'));

    $response->assertRedirect(route('admin.configuracion.index'));
    $response->assertSessionHas('success');
});

test('analista wfm puede ejecutar comandos de mantenimiento', function () {
    $user = User::factory()->create();
    $analistaRole = Role::firstOrCreate(['name' => 'analista-wfm']);
    $user->assignRole($analistaRole);

    $response = $this->actingAs($user)->post(route('admin.configuracion.maintenance'), [
        'command' => 'optimize'
    ]);

    $response->assertRedirect(route('admin.configuracion.index'));
    $response->assertSessionHas('success');
});
