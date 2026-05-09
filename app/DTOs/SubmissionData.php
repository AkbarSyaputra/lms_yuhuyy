<?php

namespace App\DTOs;

use App\Enums\SubmissionStatus;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class SubmissionData extends Data
{
    public function __construct(
        public ?string $content = null,
        public ?UploadedFile $file = null,
        public SubmissionStatus $status = SubmissionStatus::Submitted,
    ) {}
}
