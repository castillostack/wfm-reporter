<?php

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('permite a un usuario autenticado ver su perfil', function () {
    $usuario = User::factory()->create();
    $empleado = Employee::factory()->create(['user_id' => $usuario->id]);

    $this->actingAs($usuario)
        ->get(route('profile.show'))
        ->assertStatus(200)
        ->assertViewHas('usuario', $usuario);
});

it('permite a un usuario autenticado acceder a la edición de perfil', function () {
    $usuario = User::factory()->create();
    $empleado = Employee::factory()->create(['user_id' => $usuario->id]);

    $this->actingAs($usuario)
        ->get(route('profile.edit'))
        ->assertStatus(200)
        ->assertViewHas('usuario', $usuario);
});

it('permite a un usuario actualizar su perfil correctamente', function () {
    $usuario = User::factory()->create([
        'name' => 'Juan Pérez',
        'email' => 'juan@example.com'
    ]);
    $empleado = Employee::factory()->create([
        'user_id' => $usuario->id,
        'first_name' => 'Juan',
        'last_name' => 'Pérez',
        'phone' => '123456789',
        'position' => 'Analista'
    ]);

    $datosActualizados = [
        'name' => 'Juan Carlos Pérez',
        'email' => 'juancarlos@example.com',
        'first_name' => 'Juan Carlos',
        'last_name' => 'Pérez García',
        'phone' => '987654321',
        'position' => 'Analista Senior'
    ];

    $this->actingAs($usuario)
        ->post(route('profile.update'), $datosActualizados)
        ->assertRedirect(route('profile.show'))
        ->assertSessionHas('success', 'Perfil actualizado correctamente.');

    $usuario->refresh();
    $usuario->load('empleado');

    expect($usuario->name)->toBe('Juan Carlos Pérez');
    expect($usuario->email)->toBe('juancarlos@example.com');
    expect($usuario->empleado->first_name)->toBe('Juan Carlos');
    expect($usuario->empleado->last_name)->toBe('Pérez García');
    expect($usuario->empleado->phone)->toBe('987654321');
    expect($usuario->empleado->position)->toBe('Analista Senior');
});

it('valida correctamente los datos al actualizar perfil', function () {
    $usuario = User::factory()->create(['email' => 'juan@example.com']);
    // Crear otro usuario con email diferente para probar duplicados
    User::factory()->create(['email' => 'otro@example.com']);

    $this->actingAs($usuario)
        ->post(route('profile.update'), [
            'name' => '',
            'email' => 'email-invalido',
        ])
        ->assertRedirect()
        ->assertSessionHasErrors(['name', 'email']);

    $this->actingAs($usuario)
        ->post(route('profile.update'), [
            'name' => 'Juan Pérez',
            'email' => 'otro@example.com', // Email ya existente
        ])
        ->assertRedirect()
        ->assertSessionHasErrors(['email']);
});

it('no permite actualizar perfil sin autenticación', function () {
    $usuario = User::factory()->create();

    $this->post(route('profile.update'), [
        'name' => 'Nuevo Nombre',
        'email' => 'nuevo@example.com'
    ])
        ->assertRedirect(route('login'));
});

it('permite actualizar solo campos del usuario sin empleado', function () {
    $usuario = User::factory()->create([
        'name' => 'María García',
        'email' => 'maria@example.com'
    ]);

    // Usuario sin empleado asociado
    expect($usuario->empleado)->toBeNull();

    $datosActualizados = [
        'name' => 'María González',
        'email' => 'mariag@example.com',
        'first_name' => 'María',
        'last_name' => 'González',
    ];

    $this->actingAs($usuario)
        ->post(route('profile.update'), $datosActualizados)
        ->assertRedirect(route('profile.show'))
        ->assertSessionHas('success', 'Perfil actualizado correctamente.');

    $usuario->refresh();

    expect($usuario->name)->toBe('María González');
    expect($usuario->email)->toBe('mariag@example.com');
    // Los campos del empleado no deberían afectar ya que no existe empleado
});

it('permite a un usuario acceder al formulario de cambio de contraseña', function () {
    $usuario = User::factory()->create();

    $this->actingAs($usuario)
        ->get(route('profile.edit-password'))
        ->assertStatus(200)
        ->assertViewIs('pages.profile-password');
});

it('permite a un usuario cambiar su contraseña correctamente', function () {
    $usuario = User::factory()->create([
        'password' => bcrypt('password_actual')
    ]);

    $this->actingAs($usuario)
        ->post(route('profile.update-password'), [
            'current_password' => 'password_actual',
            'password' => 'nueva_password_123',
            'password_confirmation' => 'nueva_password_123'
        ])
        ->assertRedirect(route('profile.show'))
        ->assertSessionHas('success', 'Contraseña actualizada correctamente.');

    // Verificar que la nueva contraseña funcione
    $this->assertTrue(auth()->attempt(['email' => $usuario->email, 'password' => 'nueva_password_123']));
});

it('valida que la contraseña actual sea correcta', function () {
    $usuario = User::factory()->create([
        'password' => bcrypt('password_actual')
    ]);

    $this->actingAs($usuario)
        ->post(route('profile.update-password'), [
            'current_password' => 'password_incorrecta',
            'password' => 'nueva_password_123',
            'password_confirmation' => 'nueva_password_123'
        ])
        ->assertRedirect()
        ->assertSessionHasErrors(['current_password']);
});

it('valida los requisitos de la nueva contraseña', function () {
    $usuario = User::factory()->create([
        'password' => bcrypt('password_actual')
    ]);

    // Contraseña demasiado corta
    $this->actingAs($usuario)
        ->post(route('profile.update-password'), [
            'current_password' => 'password_actual',
            'password' => '123',
            'password_confirmation' => '123'
        ])
        ->assertRedirect()
        ->assertSessionHasErrors(['password']);

    // Contraseñas no coinciden
    $this->actingAs($usuario)
        ->post(route('profile.update-password'), [
            'current_password' => 'password_actual',
            'password' => 'nueva_password_123',
            'password_confirmation' => 'diferente_password_123'
        ])
        ->assertRedirect()
        ->assertSessionHasErrors(['password']);

    // Nueva contraseña igual a la actual
    $this->actingAs($usuario)
        ->post(route('profile.update-password'), [
            'current_password' => 'password_actual',
            'password' => 'password_actual',
            'password_confirmation' => 'password_actual'
        ])
        ->assertRedirect()
        ->assertSessionHasErrors(['password']);
});

it('no permite cambiar contraseña sin autenticación', function () {
    $usuario = User::factory()->create();

    $this->post(route('profile.update-password'), [
        'current_password' => 'password_actual',
        'password' => 'nueva_password_123',
        'password_confirmation' => 'nueva_password_123'
    ])
        ->assertRedirect(route('login'));
});
