<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Ledger from '@/Components/Ledger.vue';
import { Head } from '@inertiajs/vue3';
import { balanceBackground, balanceColor } from '@/helpers.js';

const props = defineProps({
    fund: Object,
    ledgers: Object,
});
</script>

<template>
   <Head :title="fund.name" />
    <AuthenticatedLayout>
        <template #header>
            {{  fund.name  }}
        </template>
        <div class="px-4">
            <section>
                <h1 class="text-xl">{{ fund.description }}</h1>
                <div :class="['my-6 p-2 text-center text-2xl rounded-md', balanceBackground(fund.balance)]">
                    <span :class="['font-light pr-2',balanceColor(fund.balance)]">Balance:</span> <span class="font-bold">{{ fund.value }}</span>
                </div>
            </section>
            <section>
                <Ledger :ledgers="ledgers" :fundId="fund.id" />
            </section>
        </div>

    </AuthenticatedLayout>
</template>
