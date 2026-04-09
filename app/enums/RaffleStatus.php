<?php

namespace App\enums;

enum RaffleStatus: string
{
    case Active = 'active';
    case Closed = 'closed';
    case Canceled = 'canceled';
    case Finished = 'finished';

    public static function allStatus(): array {
        return [
            self::Active,
            self::Closed,
            self::Canceled,
            self::Finished
        ];
    }

    public function isInInactiveStatus(): bool{
        return match($this){
            self::Active => false,
            self::Closed,
            self::Canceled,
            self::Finished => true
        };
    }

    public function color(): string {
    return match($this) {
        self::Active   => 'green',
        self::Canceled => 'red',
        self::Closed   => 'gray',
        self::Finished => 'gold',
    };
}
}
