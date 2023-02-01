export function balanceBackground(amount) {
    if(amount > 0) {
        return 'bg-lime-500/20'
    }
    if(amount < 0) {
        return 'bg-red-500/20'
    }
    return 'bg-amber-500/20'
}
