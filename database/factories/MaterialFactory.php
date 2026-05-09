<?php

namespace Database\Factories;

use App\Enums\MaterialType;
use App\Models\Course;
use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialFactory extends Factory
{
    protected $model = Material::class;

    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
            'type' => $this->faker->randomElement(MaterialType::cases())->value,
            'file_path' => null,
            'external_url' => null,
            'order' => $this->faker->numberBetween(1, 100),
            'is_published' => $this->faker->boolean(80), // 80% chance to be published
        ];
    }
}
