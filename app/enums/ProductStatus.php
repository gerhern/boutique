<?php

namespace App\enums;

enum ProductStatus: string
{
    case Available = 'available';
    case Reserved = 'reserved';
    case Sold = 'sold';

    public static function all(): array{
        return [
            self::Available,
            self::Reserved,
            self::Sold
        ];
    }

    public static function statusRestricted(): array{
        return [
            self::Reserved,
            self::Sold
        ];
    }

    public static function options(): array
{
    return collect(self::cases())->mapWithKeys(function ($status) {
        return [$status->value => $status->name];
    })->toArray();
}
}
