<?php

namespace App\Enums;

enum Roles: int
{
    case ADMIN = 1;
    case RESIDENT = 2;
    case SUPPLIER = 3;
}
