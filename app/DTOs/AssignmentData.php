<?php

namespace App\DTOs;

use App\Enums\AssignmentType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class AssignmentData extends Data
{
    public function __construct(
        public string $title,
        public AssignmentType $type,
        public Optional|string $description,
        public Optional|int $max_score,
        public Optional|string $due_date,
        public bool $is_published = false,
    ) {}
}
