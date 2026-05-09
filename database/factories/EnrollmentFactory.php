<?php

namespace Database\Factories;

use App\Enums\EnrollmentStatus;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    protected $model = Enrollment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'course_id' => Course::factory(),
            'status' => EnrollmentStatus::Active,
            'progress_percentage' => $this->faker->numberBetween(0, 100),
            'enrolled_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'completed_at' => null,
        ];
    }
}
