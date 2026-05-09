<?php

namespace App\Enums;

enum MaterialType: string
{
    case Text = 'text';
    case Video = 'video';
    case File = 'file';
    case Link = 'link';

    public function label(): string
    {
        return match ($this) {
            self::Text => 'Text',
            self::Video => 'Video',
            self::File => 'File',
            self::Link => 'Link',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Text => 'file-text',
            self::Video => 'video',
            self::File => 'paperclip',
            self::Link => 'link',
        };
    }
}
