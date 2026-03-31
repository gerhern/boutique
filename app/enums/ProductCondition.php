<?php

namespace App\enums;

enum ProductCondition: string
{
    case New = 'new';
    case Used = 'used';

    public static function all(){
        return [
            self::New,
            self::Used
        ];
    }
}
