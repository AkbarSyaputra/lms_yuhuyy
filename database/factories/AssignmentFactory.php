<?php

namespace Database\Factories;

use App\Enums\AssignmentType;
use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignmentFactory extends Factory
{
    protected $model = Assignment::class;

    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraphs(2, true),
            'type' => $this->faker->randomElement(AssignmentType::cases())->value,
            'max_score' => $this->faker->randomElement([50, 100]),
            'due_date' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'is_published' => $this->faker->boolean(80), // 80% chance to be published
        ];
    }
}
