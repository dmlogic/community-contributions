<?php
namespace App\Enums;

enum LedgerTypes: string
{
    case RESIDENT_REQUEST = 'RESIDENT_REQUEST';
    case RESIDENT_ADDITIONAL = 'RESIDENT_ADDITIONAL';
    case RESIDENT_OTHER = 'RESIDENT_OTHER';
    case RESIDENT_OFFLINE = 'RESIDENT_OFFLINE';
    case ADMIN_ADJUSTMENT = 'ADMIN_ADJUSTMENT';
    case DISBURSEMENT = 'DISBURSEMENT';

    public static function residentTypes() {
        return [
            LedgerTypes::RESIDENT_REQUEST->value,
            LedgerTypes::RESIDENT_ADDITIONAL->value,
            LedgerTypes::RESIDENT_OTHER->value,
        ];
    }
}
