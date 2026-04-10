<?php

namespace App\enums;

enum ProductCondition: string
{
    case New = 'new';
    case Used = 'used';

    public static function allConditions(){
        return [
            self::New,
            self::Used
        ];
    }
}
