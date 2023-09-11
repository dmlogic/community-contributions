<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import LedgerEntry from '@/Components/LedgerEntry.vue';
import { balanceBackground, formatDate } from '@/helpers.js';

import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    payments: Object,
});

</script>

<template>
    <Head title="Statement" />
    <AuthenticatedLayout>
        <template #header>
            Your payment history
        </template>

        <table v-if="payments.length" class="w-full table-auto">
            <thead>
                <tr>
                    <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Date</th>
                    <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Fund</th>
                    <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Details</th>
                    <th class="border-b border-blue-gray-50 py-3 px-5 text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
            <LedgerEntry
                v-for="ledger in payments"
                :model="ledger"
                :admin="false" />
        </tbody>
        </table>
        <div v-else>
            You have not made any payments
        </div>
    </AuthenticatedLayout>
</template>
