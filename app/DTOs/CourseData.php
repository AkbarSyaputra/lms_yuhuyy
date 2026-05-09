<?php

namespace App\DTOs;

use App\Enums\CourseStatus;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class CourseData extends Data
{
    public function __construct(
        public string $title,
        public string|Optional $slug,
        #[Enum(CourseStatus::class)]
        public CourseStatus $status,
        public string|Optional $description,
        public string|Optional $thumbnail,
        public int|Optional $category_id,
        public int|Optional $created_by,
        public int|Optional $max_students,
        public string|Optional $start_date,
        public string|Optional $end_date,
    ) {}
}
