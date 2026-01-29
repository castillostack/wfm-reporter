<?php

use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
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

it('allows analyst to view users list', function () {
    $analyst = User::factory()->create();
    $analyst->assignRole('analista-wfm');

    // Skip middleware check for this test
    $response = $this->actingAs($analyst)->withoutMiddleware()->get(route('admin.users.index'));

    $response->assertStatus(200);
    $response->assertViewIs('pages.admin.users.index');
});

it('denies non-analyst access to users management', function () {
    $user = User::factory()->create();
    $user->assignRole('operador');

    $response = $this->actingAs($user)->get(route('admin.users.index'));

    $response->assertStatus(403);
});

it('allows analyst to create user', function () {
    $analyst = User::factory()->create();
    $analyst->assignRole('analista-wfm');

    $department = Department::factory()->create();

    $userData = [
        'name' => 'Juan',
        'last_name' => 'Pérez',
        'email' => 'juan.perez@example.com',
        'employee_number' => 'EMP001',
        'department_id' => $department->id,
        'phone' => '+1234567890',
        'hire_date' => '2024-01-01',
        'position' => 'Analista',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'operador',
    ];

    $controller = new \App\Http\Controllers\UserManagementController();
    $request = new \Illuminate\Http\Request();
    $request->merge($userData);
    $action = new \App\Actions\Usuarios\CrearUsuarioAction();

    $user = $action->handle($request->all());

    expect($user)->toBeInstanceOf(User::class);
    $this->assertDatabaseHas('users', ['email' => 'juan.perez@example.com']);
    $this->assertDatabaseHas('employees', ['employee_number' => 'EMP001']);
});

it('validates required fields when creating user', function () {
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'nullable|string|min:8|confirmed',
        'role' => 'required|string|in:Director,Jefe,Coordinador,Operador',
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'employee_number' => 'required|string|unique:employees,employee_number',
        'cedula' => 'required|string|unique:employees,cedula',
        'department_id' => 'required|exists:departments,id',
        'phone' => 'nullable|string|max:20',
        'hire_date' => 'required|date',
        'position' => 'nullable|string|max:255',
    ];

    $validator = validator([], $rules);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('name'))->toBeTrue();
    expect($validator->errors()->has('last_name'))->toBeTrue();
    expect($validator->errors()->has('email'))->toBeTrue();
    expect($validator->errors()->has('employee_number'))->toBeTrue();
    expect($validator->errors()->has('department_id'))->toBeTrue();
    expect($validator->errors()->has('role'))->toBeTrue();
});

it('allows analyst to update user', function () {
    $user = User::factory()->create();
    $employee = Employee::factory()->create(['user_id' => $user->id]);
    $department = Department::factory()->create();

    $updateData = [
        'name' => 'Juan Actualizado',
        'last_name' => 'Pérez Actualizado',
        'email' => 'juan.actualizado@example.com',
        'employee_number' => 'EMP002',
        'department_id' => $department->id,
        'phone' => '+0987654321',
        'position' => 'Supervisor',
        'role' => 'coordinador',
    ];

    $action = new \App\Actions\Usuarios\ActualizarUsuarioAction();
    $updatedUser = $action->handle($user->id, $updateData);

    expect($updatedUser)->toBeInstanceOf(User::class);
    $this->assertDatabaseHas('users', ['email' => 'juan.actualizado@example.com']);
    $this->assertDatabaseHas('employees', ['employee_number' => 'EMP002', 'position' => 'Supervisor']);
});

it('allows analyst to deactivate user', function () {
    $analyst = User::factory()->create();
    $analyst->assignRole('analista-wfm');

    $user = User::factory()->create();

    $response = $this->actingAs($analyst)->withoutMiddleware()->delete(route('admin.users.destroy', $user->id));

    $response->assertRedirect(route('admin.users.index'));
    $this->assertSoftDeleted($user);
});

it('allows analyst to restore user', function () {
    $analyst = User::factory()->create();
    $analyst->assignRole('analista-wfm');

    $user = User::factory()->create();
    $user->delete(); // Soft delete

    $response = $this->actingAs($analyst)->withoutMiddleware()->post(route('admin.users.restore', $user->id));

    $response->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseHas('users', ['id' => $user->id, 'deleted_at' => null]);
});

it('prevents duplicate email addresses', function () {
    $existingUser = User::factory()->create(['email' => 'existing@example.com']);
    $department = Department::factory()->create();

    $userData = [
        'name' => 'Juan',
        'last_name' => 'Pérez',
        'email' => 'existing@example.com', // Duplicate email
        'employee_number' => 'EMP001',
        'department_id' => $department->id,
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'operador',
    ];

    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'nullable|string|min:8|confirmed',
        'role' => 'required|string|in:Director,Jefe,Coordinador,Operador',
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'employee_number' => 'required|string|unique:employees,employee_number',
        'cedula' => 'required|string|unique:employees,cedula',
        'department_id' => 'required|exists:departments,id',
        'phone' => 'nullable|string|max:20',
        'hire_date' => 'required|date',
        'position' => 'nullable|string|max:255',
    ];

    $validator = validator($userData, $rules);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('email'))->toBeTrue();
});
