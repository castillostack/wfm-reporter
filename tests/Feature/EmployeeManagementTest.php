<?php

use App\Models\Employee;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
   // Crear roles necesarios para los tests
   Role::firstOrCreate(['name' => 'analista-wfm']);
   Role::firstOrCreate(['name' => 'operador']);
   Role::firstOrCreate(['name' => 'coordinador']);
   Role::firstOrCreate(['name' => 'jefe-departamento']);
   Role::firstOrCreate(['name' => 'director-nacional']);
});

test('permite a analista-wfm ver lista de empleados', function () {
   $analista = User::factory()->create();
   $analista->assignRole('analista-wfm');

   $response = $this->actingAs($analista)->withoutMiddleware()->get(route('admin.employees.index'));

   $response->assertStatus(200);
   $response->assertViewIs('pages.admin.employees.index');
});

test('deniega acceso a gestión de empleados a no analistas', function () {
   $usuario = User::factory()->create();

   $response = $this->actingAs($usuario)->get(route('admin.employees.index'));

   $response->assertStatus(403);
});

test('permite a analista-wfm crear empleado', function () {
   $analista = User::factory()->create();
   $analista->assignRole('analista-wfm');

   $departamento = Department::factory()->create();

   $datosEmpleado = [
      'first_name' => 'Juan',
      'last_name' => 'Pérez',
      'employee_number' => 'EMP001',
      'email' => 'juan.perez@empresa.com',
      'phone' => '+56912345678',
      'position' => 'Analista',
      'department_id' => $departamento->id,
      'hire_date' => '2024-01-01',
      'salary' => 50000,
      'address' => 'Dirección de prueba',
      'emergency_contact_name' => 'María Pérez',
      'emergency_contact_phone' => '+56987654321',
   ];

   $response = $this->actingAs($analista)->withoutMiddleware()->post(route('admin.employees.store'), $datosEmpleado);

   // Debug: verificar si hay errores de validación
   if ($response->getStatusCode() !== 302) {
      dd($response->getContent());
   }


   $this->assertDatabaseHas('employees', [
      'first_name' => 'Juan',
      'last_name' => 'Pérez',
      'employee_number' => 'EMP001',
      'email' => 'juan.perez@empresa.com',
   ]);
});

test('valida campos requeridos al crear empleado', function () {
   $analista = User::factory()->create();
   $analista->assignRole('analista-wfm');

   $response = $this->actingAs($analista)->withoutMiddleware()->post(route('admin.employees.store'), []);

   $response->assertRedirect();
   $response->assertSessionHasErrors(['first_name', 'last_name', 'employee_number', 'email', 'position', 'department_id']);
});

test('permite a analista-wfm ver detalles de empleado', function () {
   $analista = User::factory()->create();
   $analista->assignRole('analista-wfm');

   $departamento = Department::factory()->create();
   $empleado = Employee::factory()->create(['department_id' => $departamento->id]);

   $response = $this->actingAs($analista)->get(route('admin.employees.show', $empleado->id));

   $response->assertStatus(200);
   $response->assertViewIs('pages.admin.employees.show');
   $response->assertViewHas('empleado');
});

test('permite a analista-wfm actualizar empleado', function () {
   $analista = User::factory()->create();
   $analista->assignRole('analista-wfm');

   $departamento = Department::factory()->create();
   $empleado = Employee::factory()->create(['department_id' => $departamento->id]);

   // Debug: verificar que el empleado se creó correctamente
   $this->assertNotNull($empleado->id, 'Employee ID should not be null');
   $this->assertDatabaseHas('employees', ['id' => $empleado->id]);

   $datosActualizados = [
      'first_name' => 'Juan Carlos',
      'last_name' => 'Pérez',
      'employee_number' => 'EMP001',
      'email' => 'juancarlos.perez@empresa.com',
      'phone' => '+56912345678',
      'position' => 'Senior Analista',
      'department_id' => $departamento->id,
      'hire_date' => '2024-01-01',
      'salary' => 60000,
   ];

   $response = $this->actingAs($analista)->put(route('admin.employees.update', $empleado->id), $datosActualizados);

   $response->assertRedirect(route('admin.employees.index'));
   $response->assertSessionHas('success');

   $this->assertDatabaseHas('employees', [
      'id' => $empleado->id,
      'first_name' => 'Juan Carlos',
      'position' => 'Senior Analista',
      'salary' => 60000,
   ]);
});

test('permite a analista-wfm desactivar empleado', function () {
   $analista = User::factory()->create();
   $analista->assignRole('analista-wfm');

   $departamento = Department::factory()->create();
   $empleado = Employee::factory()->create(['department_id' => $departamento->id]);

   $response = $this->actingAs($analista)->delete(route('admin.employees.destroy', $empleado->id));

   $response->assertRedirect(route('admin.employees.index'));
   $response->assertSessionHas('success');

   $this->assertSoftDeleted('employees', ['id' => $empleado->id]);
});

test('permite a analista-wfm restaurar empleado', function () {
   $analista = User::factory()->create();
   $analista->assignRole('analista-wfm');

   $departamento = Department::factory()->create();
   $empleado = Employee::factory()->create(['department_id' => $departamento->id]);
   $empleado->delete();

   $response = $this->actingAs($analista)->withoutMiddleware()->post(route('admin.employees.restore', $empleado->id));

   $response->assertRedirect(route('admin.employees.index'));
   $response->assertSessionHas('success');

   $this->assertDatabaseHas('employees', ['id' => $empleado->id, 'deleted_at' => null]);
});

test('previene números de empleado duplicados', function () {
   $analista = User::factory()->create();
   $analista->assignRole('analista-wfm');

   $departamento = Department::factory()->create();
   Employee::factory()->create([
      'employee_number' => 'EMP001',
      'department_id' => $departamento->id
   ]);

   $datosEmpleado = [
      'first_name' => 'Juan',
      'last_name' => 'Pérez',
      'employee_number' => 'EMP001', // Duplicado
      'email' => 'juan.perez@empresa.com',
      'position' => 'Analista',
      'department_id' => $departamento->id,
   ];

   $response = $this->actingAs($analista)->withoutMiddleware()->post(route('admin.employees.store'), $datosEmpleado);

   $response->assertRedirect();
   $response->assertSessionHasErrors('employee_number');
});

test('filtra empleados por búsqueda', function () {
   $analista = User::factory()->create();
   $analista->assignRole('analista-wfm');

   $departamento = Department::factory()->create();

   Employee::factory()->create([
      'first_name' => 'Juan',
      'last_name' => 'Pérez',
      'employee_number' => 'EMP001',
      'department_id' => $departamento->id
   ]);

   Employee::factory()->create([
      'first_name' => 'María',
      'last_name' => 'González',
      'employee_number' => 'EMP002',
      'department_id' => $departamento->id
   ]);

   $response = $this->actingAs($analista)->withoutMiddleware()->get(route('admin.employees.index', ['search' => 'Juan']));

   $response->assertStatus(200);
   $response->assertViewHas('empleados');
   $empleados = $response->viewData('empleados');
   expect($empleados)->toHaveCount(1);
   expect($empleados->first()->first_name)->toBe('Juan');
});

test('filtra empleados por departamento', function () {
   $analista = User::factory()->create();
   $analista->assignRole('analista-wfm');

   $departamento1 = Department::factory()->create();
   $departamento2 = Department::factory()->create();

   Employee::factory()->create(['department_id' => $departamento1->id]);
   Employee::factory()->create(['department_id' => $departamento2->id]);

   $response = $this->actingAs($analista)->withoutMiddleware()->get(route('admin.employees.index', ['department' => $departamento1->id]));

   $response->assertStatus(200);
   $response->assertViewHas('empleados');
   $empleados = $response->viewData('empleados');
   expect($empleados)->toHaveCount(1);
   expect($empleados->first()->department_id)->toBe($departamento1->id);
});
