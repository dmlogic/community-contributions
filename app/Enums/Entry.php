<?php
namespace App\Enums;

enum Entry
{
    case RESIDENT_REQUEST;
    case RESIDENT_ADDITIONAL;
    case RESIDENT_OTHER;
    case RESIDENT_OFFLINE;
    case ADMIN_ADJUSTMENT;
    case DISBURSEMENT;
}
