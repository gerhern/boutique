<?php

namespace App\enums;

enum ProductCondition: string
{
    case New = 'new';
    case Used = 'used';

    public static function getConditions(){
        return [
            self::New,
            self::Used
        ];
    }
}
