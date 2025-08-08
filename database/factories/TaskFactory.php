<?php

namespace Database\Factories;

use App\Enums\RecurringIntervalEnum;
use App\Enums\StatusEnum;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(2, false),
            'status' => fake()->randomElement(StatusEnum::class),
            'due_date' => fake()->date(),
            'is_recurring' => fake()->boolean(),
            'recurring_interval' => fake()->randomElement(RecurringIntervalEnum::class),
        ];
    }
}
