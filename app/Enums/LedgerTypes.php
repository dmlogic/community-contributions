<?php

namespace App\Enums;

enum LedgerTypes: string
{
    /**
     * An online (verified) payment by a resident in response to a fund request
     */
    case RESIDENT_REQUEST = 'RESIDENT_REQUEST';
    /**
     * An additional online (verified) payment by a resident
     */
    case RESIDENT_ADDITIONAL = 'RESIDENT_ADDITIONAL';
    /**
     * A notice by a resident that a unverifed payment has been made
     */
    case RESIDENT_OFFLINE = 'RESIDENT_OFFLINE';
    /**
     * A manual adjustment to a fund balance by an administrator
     */
    case ADMIN_ADJUSTMENT = 'ADMIN_ADJUSTMENT';
    /**
     * A log of direct expenditure from a fund
     */
    case EXPENDITURE = 'EXPENDITURE';
    /**
     * A log of fees paid from a fund (e.g. payment provider charges)
     */
    case FEES = 'FEES';
}
