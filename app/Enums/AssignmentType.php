<?php

namespace App\Enums;

enum AssignmentType: string
{
    case FileUpload = 'file_upload';
    case Text = 'text';
    case Quiz = 'quiz';

    public function label(): string
    {
        return match ($this) {
            self::FileUpload => 'File Upload',
            self::Text => 'Text',
            self::Quiz => 'Quiz',
        };
    }
}
