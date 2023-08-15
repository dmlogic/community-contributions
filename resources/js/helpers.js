import { usePage } from '@inertiajs/vue3'
import moment from 'moment'

export function can(doWhat) {
    return usePage().props.can[doWhat] ?? false;
}
export function balanceBackground(amount, type) {
    if(typeof type !='undefined' && type == "EXPENDITURE") {
        amount = amount *-1
    }
    if(amount > 0) {
        return 'bg-lime-500/20'
    }
    if(amount < 0) {
        return 'bg-red-500/20'
    }
    return 'bg-amber-500/20'
}
export function balanceColor(amount) {
    if(amount > 0) {
        return 'text-lime-800'
    }
    if(amount < 0) {
        return 'text-red-800'
    }
    return 'text-amber-800'
}

export function formatDate(rawDate) {
    return moment(rawDate).format('DD/MM/YYYY')
}

export function ledgerTypes() {
    return {
        'RESIDENT_REQUEST': 'Funding request',
        'RESIDENT_ADDITIONAL': 'Voluntary payment',
        'RESIDENT_OFFLINE': 'Offline payment',
        'ADMIN_ADJUSTMENT': 'Ledger adjustment',
        'EXPENDITURE': 'Expenditure',
        'FEES': 'Banking fees'
    }
}
