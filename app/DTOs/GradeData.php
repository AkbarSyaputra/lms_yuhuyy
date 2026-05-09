<?php

namespace App\DTOs;

use App\Enums\SubmissionStatus;
use Spatie\LaravelData\Data;

class GradeData extends Data
{
    public function __construct(
        public int $score,
        public ?string $feedback = null,
        public SubmissionStatus $status = SubmissionStatus::Graded,
    ) {}
}
