<?php

namespace App\enums;

enum ProductCondition: string
{
    case New = 'new';
    case Used = 'used';

    public static function getConditions()
    {
        return [
            self::New,
            self::Used
        ];
    }

    public static function getConditionsValues(): array
    {
        return collect(self::cases())->mapWithKeys(function ($condition) {
            return [$condition->value => $condition->name];
        })->toArray();
    }
}
