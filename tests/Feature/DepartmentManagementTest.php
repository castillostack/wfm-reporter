<?php

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
   // Crear roles necesarios para los tests
   Role::firstOrCreate(['name' => 'analista-wfm']);
   Role::firstOrCreate(['name' => 'operador']);
});

it('allows analyst to view departments list', function () {
   $analyst = User::factory()->create();
   $analyst->assignRole('analista-wfm');

   Department::factory()->create(['name' => 'Recursos Humanos']);
   Department::factory()->create(['name' => 'Tecnología']);

   $response = $this->actingAs($analyst)->get(route('admin.departments.index'));

   $response->assertStatus(200);
   $response->assertViewIs('pages.admin.departments.index');
   $response->assertViewHas('departamentos');
});

it('allows analyst to create department', function () {
   $analyst = User::factory()->create();
   $analyst->assignRole('analista-wfm');

   $departmentData = [
      'name' => 'Nuevo Departamento',
   ];

   $response = $this->actingAs($analyst)->post(route('admin.departments.store'), $departmentData);

   $response->assertRedirect(route('admin.departments.index'));
   $this->assertDatabaseHas('departments', $departmentData);
});

it('validates required fields when creating department', function () {
   $analyst = User::factory()->create();
   $analyst->assignRole('analista-wfm');

   $response = $this->actingAs($analyst)->post(route('admin.departments.store'), []);

   $response->assertRedirect();
   $response->assertSessionHasErrors('name');
});

it('prevents duplicate department names', function () {
   $analyst = User::factory()->create();
   $analyst->assignRole('analista-wfm');

   Department::create(['name' => 'Recursos Humanos']);

   $response = $this->actingAs($analyst)->post(route('admin.departments.store'), [
      'name' => 'Recursos Humanos',
   ]);

   $response->assertRedirect();
   $response->assertSessionHasErrors('name');
});

it('allows analyst to update department', function () {
   $analyst = User::factory()->create();
   $analyst->assignRole('analista-wfm');

   $department = Department::factory()->create(['name' => 'Viejo Nombre']);

   $response = $this->actingAs($analyst)->put(route('admin.departments.update', $department), [
      'name' => 'Nuevo Nombre',
   ]);

   $response->assertRedirect(route('admin.departments.index'));
   $this->assertDatabaseHas('departments', ['name' => 'Nuevo Nombre']);
});

it('allows analyst to deactivate department', function () {
   $analyst = User::factory()->create();
   $analyst->assignRole('analista-wfm');

   $department = Department::factory()->create();

   $response = $this->actingAs($analyst)->delete(route('admin.departments.destroy', $department));

   $response->assertRedirect(route('admin.departments.index'));
   $this->assertSoftDeleted($department);
});

it('allows analyst to restore department', function () {
   $analyst = User::factory()->create();
   $analyst->assignRole('analista-wfm');

   $department = Department::factory()->create();
   $department->delete();

   $response = $this->actingAs($analyst)->post(route('admin.departments.restore', $department));

   $response->assertRedirect(route('admin.departments.index'));
   $this->assertDatabaseHas('departments', ['id' => $department->id, 'deleted_at' => null]);
});

it('denies non-analyst access to departments management', function () {
   $user = User::factory()->create();
   $user->assignRole('operador');

   $response = $this->actingAs($user)->get(route('admin.departments.index'));

   $response->assertStatus(403);
});
