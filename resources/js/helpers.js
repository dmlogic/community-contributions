import { usePage } from '@inertiajs/vue3'

export function can(doWhat) {
    return usePage().props.can[doWhat] ?? false;
}
export function balanceBackground(amount) {
    if(amount > 0) {
        return 'bg-lime-500/20'
    }
    if(amount < 0) {
        return 'bg-red-500/20'
    }
    return 'bg-amber-500/20'
}
