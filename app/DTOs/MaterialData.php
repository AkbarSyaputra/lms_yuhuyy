<?php

namespace App\DTOs;

use App\Enums\MaterialType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class MaterialData extends Data
{
    public function __construct(
        public string $title,
        public MaterialType $type,
        public Optional|string $content,
        public Optional|string $file_path,
        public Optional|string $external_url,
        public Optional|int $order,
        public bool $is_published = false,
    ) {}
}
