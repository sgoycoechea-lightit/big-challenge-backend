<?php

declare(strict_types=1);

namespace App\Enums;

enum SubmissionStatus: string
{
    case Pending = 'PENDING';
    case InProgress = 'IN_PROGRESS';
    case Done = 'DONE';

    public static function toArray(): array
    {
        return [
            self::Pending->value => self::Pending->value,
            self::InProgress->value => self::InProgress->value,
            self::Done->value => self::Done->value,
        ];
    }
}
