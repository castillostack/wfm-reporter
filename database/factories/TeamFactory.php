<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'name' => fake()->unique()->words(2, true),
            'description' => fake()->optional(0.7)->sentence(),
            'leader_id' => \App\Models\User::factory(),
            'department_id' => \App\Models\Department::factory(),
        ];
    }
}
