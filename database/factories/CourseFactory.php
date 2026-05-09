<?php

namespace Database\Factories;

use App\Enums\CourseStatus;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        $title = $this->faker->sentence();

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraphs(3, true),
            'thumbnail' => null,
            'category_id' => CourseCategory::factory(),
            'created_by' => User::factory(),
            'status' => CourseStatus::Published,
            'max_students' => $this->faker->optional()->numberBetween(10, 100),
            'start_date' => now()->addDays(7),
            'end_date' => now()->addMonths(3),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CourseStatus::Draft,
        ]);
    }
}
