<?php

namespace App\enums;

enum ProductStatus: string
{
    case Available = 'available';
    case Reserved = 'reserved';
    case Sold = 'Sold';

    public static function all(): array{
        return [
            self::Available,
            self::Reserved,
            self::Sold
        ];

    }
}
