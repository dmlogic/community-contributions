<?php

namespace App\Enums;

enum Roles: int
{
    case ADMIN = 1;
    case RESIDENT = 2;
    case SUPPLIER = 3;

    public static function forForms()
    {
        return [
            ['value' => Roles::RESIDENT->value, 'label' => 'Resident'],
            ['value' => Roles::SUPPLIER->value, 'label' => 'Supplier'],
            ['value' => Roles::ADMIN->value, 'label' => 'Administrator'],
        ];
    }
}
