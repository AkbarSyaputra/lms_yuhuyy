<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class CategoryData extends Data
{
    public function __construct(
        public string $name,
        public string|Optional $slug,
        public string|Optional $description,
        public int|Optional $parent_id,
    ) {}
}
