<?php

use App\Models\Schedule;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

test('route model binding handles invalid schedule IDs gracefully', function () {
    // Test that accessing a non-numeric ID doesn't cause a database error
    $response = $this->get('/horarios/asignacion-masiva');

    // Should return 404 Not Found instead of 500 Internal Server Error
    $response->assertStatus(404);
});

test('route model binding works with valid numeric IDs', function () {
    // Create test data
    $employee = Employee::factory()->create();
    $schedule = Schedule::factory()->create(['employee_id' => $employee->id]);

    // Test that accessing a valid numeric ID works
    $response = $this->get("/horarios/{$schedule->id}");

    // Should return the appropriate response (may be 403 due to permissions, but not 500)
    expect($response->status())->toBeIn([200, 403, 302]);
});
