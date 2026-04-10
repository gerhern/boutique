<?php

namespace App\enums;

enum ProductStatus: string
{
    case Available = 'available';
    case Reserved = 'reserved';
    case Sold = 'sold';

    public static function allStatus(): array{
        return [
            self::Available,
            self::Reserved,
            self::Sold
        ];
    }

    public function isRestricted(): bool{
        return match($this){
            self::Available => false,
            self::Reserved,
            self::Sold => true
        };
    }

    public static function options(): array
{
    return collect(self::cases())->mapWithKeys(function ($status) {
        return [$status->value => $status->name];
    })->toArray();
}
}
