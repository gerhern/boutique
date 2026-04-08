<?php

namespace App\enums;

enum RaffleStatus: string
{
    case Active = 'active';
    case Closed = 'closed';
    case Finished = 'finished';
}
