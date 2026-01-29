<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory {
   protected $model = Employee::class;

   public function definition(): array {
      return [
         'user_id' => User::factory(),
         'employee_number' => $this->faker->unique()->numerify('EMP###'),
         'first_name' => $this->faker->firstName(),
         'last_name' => $this->faker->lastName(),
         'phone' => $this->faker->optional()->phoneNumber(),
         'hire_date' => $this->faker->date(),
         'position' => $this->faker->jobTitle(),
         'department_id' => Department::factory(),
      ];
   }
}
