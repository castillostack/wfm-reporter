<?php

use App\Models\Department;
use App\Models\Employee;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Crear roles necesarios para los tests
    Role::firstOrCreate(['name' => 'analista-wfm']);
    Role::firstOrCreate(['name' => 'coordinador']);
});

it('puede acceder al listado de equipos con rol correcto', function () {
    $user = User::factory()->create();
    $user->assignRole('analista-wfm');

    $response = $this->actingAs($user)->get(route('admin.teams.index'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.admin.teams.index');
});

it('no puede acceder sin rol analista-wfm', function () {
    $user = User::factory()->create();
    $user->assignRole('coordinador');

    $response = $this->actingAs($user)->get(route('admin.teams.index'));

    $response->assertStatus(403);
});

it('puede crear equipo válido', function () {
    $user = User::factory()->create();
    $user->assignRole('analista-wfm');

    $department = Department::factory()->create();
    $leader = User::factory()->create();

    $teamData = [
        'name' => 'Equipo de Desarrollo',
        'description' => 'Equipo encargado del desarrollo de software',
        'leader_id' => $leader->id,
        'department_id' => $department->id,
    ];

    $response = $this->actingAs($user)->post(route('admin.teams.store'), $teamData);

    $response->assertRedirect(route('admin.teams.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('teams', [
        'name' => 'Equipo de Desarrollo',
        'description' => 'Equipo encargado del desarrollo de software',
        'leader_id' => $leader->id,
        'department_id' => $department->id,
    ]);
});

it('no puede crear equipo con nombre duplicado', function () {
    $user = User::factory()->create();
    $user->assignRole('analista-wfm');

    Team::factory()->create(['name' => 'Equipo Existente']);

    $teamData = [
        'name' => 'Equipo Existente',
        'description' => 'Descripción diferente',
    ];

    $response = $this->actingAs($user)->post(route('admin.teams.store'), $teamData);

    $response->assertRedirect();
    $response->assertSessionHasErrors('name');
});

it('puede actualizar equipo', function () {
    $user = User::factory()->create();
    $user->assignRole('analista-wfm');

    $team = Team::factory()->create();
    $newLeader = User::factory()->create();
    $newDepartment = Department::factory()->create();

    $updateData = [
        'name' => 'Equipo Actualizado',
        'description' => 'Descripción actualizada',
        'leader_id' => $newLeader->id,
        'department_id' => $newDepartment->id,
    ];

    $response = $this->actingAs($user)->put(route('admin.teams.update', $team), $updateData);

    $response->assertRedirect(route('admin.teams.index'));
    $response->assertSessionHas('success');

    $team->refresh();
    expect($team->name)->toBe('Equipo Actualizado');
    expect($team->description)->toBe('Descripción actualizada');
    expect($team->leader_id)->toBe($newLeader->id);
    expect($team->department_id)->toBe($newDepartment->id);
});

it('no puede actualizar con nombre duplicado', function () {
    $user = User::factory()->create();
    $user->assignRole('analista-wfm');

    $team1 = Team::factory()->create(['name' => 'Equipo 1']);
    $team2 = Team::factory()->create(['name' => 'Equipo 2']);

    $updateData = [
        'name' => 'Equipo 1', // Intenta usar el nombre de team1
    ];

    $response = $this->actingAs($user)->put(route('admin.teams.update', $team2), $updateData);

    $response->assertRedirect();
    $response->assertSessionHasErrors('name');
});

it('puede desactivar equipo sin empleados', function () {
    $user = User::factory()->create();
    $user->assignRole('analista-wfm');

    $team = Team::factory()->create();

    $response = $this->actingAs($user)->delete(route('admin.teams.destroy', $team));

    $response->assertRedirect(route('admin.teams.index'));
    $response->assertSessionHas('success');

    $this->assertSoftDeleted($team);
});

it('no puede desactivar equipo con empleados activos', function () {
    $user = User::factory()->create();
    $user->assignRole('analista-wfm');

    $team = Team::factory()->create();
    $employee = Employee::factory()->create(['team_id' => $team->id]);

    $response = $this->actingAs($user)->delete(route('admin.teams.destroy', $team));

    $response->assertRedirect();
    $response->assertSessionHas('error');

    $this->assertDatabaseHas('teams', ['id' => $team->id, 'deleted_at' => null]);
});

it('puede ver detalles del equipo', function () {
    $user = User::factory()->create();
    $user->assignRole('analista-wfm');

    $team = Team::factory()->create();

    $response = $this->actingAs($user)->get(route('admin.teams.show', $team));

    $response->assertStatus(200);
    $response->assertViewIs('pages.admin.teams.show');
    $response->assertViewHas('team', $team);
});

it('puede acceder al formulario de creación', function () {
    $user = User::factory()->create();
    $user->assignRole('analista-wfm');

    $response = $this->actingAs($user)->get(route('admin.teams.create'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.admin.teams.create');
});

it('puede acceder al formulario de edición', function () {
    $user = User::factory()->create();
    $user->assignRole('analista-wfm');

    $team = Team::factory()->create();

    $response = $this->actingAs($user)->get(route('admin.teams.edit', $team));

    $response->assertStatus(200);
    $response->assertViewIs('pages.admin.teams.edit');
    $response->assertViewHas('team', $team);
});

it('puede buscar equipos', function () {
    $user = User::factory()->create();
    $user->assignRole('analista-wfm');

    Team::factory()->create(['name' => 'Equipo Desarrollo']);
    Team::factory()->create(['name' => 'Equipo Marketing']);

    $response = $this->actingAs($user)->get(route('admin.teams.index', ['search' => 'Desarrollo']));

    $response->assertStatus(200);
    $response->assertViewHas('equipos');
    $equipos = $response->viewData('equipos');
    expect($equipos->count())->toBe(1);
    expect($equipos->first()->name)->toBe('Equipo Desarrollo');
});

it('puede restaurar equipo', function () {
    $user = User::factory()->create();
    $user->assignRole('analista-wfm');

    $team = Team::factory()->create();
    $team->delete();

    $response = $this->actingAs($user)->post(route('admin.teams.restore', $team->id));

    $response->assertRedirect(route('admin.teams.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('teams', ['id' => $team->id, 'deleted_at' => null]);
});
