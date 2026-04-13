<?php

namespace App\enums;

enum Role : string
{
    case Admin = 'admin';
    case User = 'user';

    public static function getRoles(): array {
        return [
            self::Admin,
            self::User
        ];
    }
}
