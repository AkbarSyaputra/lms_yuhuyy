<?php

namespace App\Enums;

enum SubmissionStatus: string
{
    case Submitted = 'submitted';
    case Graded = 'graded';
    case Returned = 'returned';

    public function label(): string
    {
        return match ($this) {
            self::Submitted => 'Submitted',
            self::Graded => 'Graded',
            self::Returned => 'Returned',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Submitted => 'yellow',
            self::Graded => 'green',
            self::Returned => 'blue',
        };
    }
}
