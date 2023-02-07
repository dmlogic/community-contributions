<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Ledger from '@/Pages/Fund/Ledger.vue';
import { Head } from '@inertiajs/vue3';
import { balanceBackground } from '@/helpers.js';

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
                <div :class="['my-6 p-2 text-center text-2xl font-bold rounded-md', balanceBackground(fund.balance)]">
                    {{ fund.value }}
                </div>
            </section>
            <section>
                <h1 class="text-lg">Activity</h1>
                <ul>
                    <li>Auto pagination (@see https://downing.tech/posts/infinite-loading-in-inertia-js)</li>
                    <li>Filter to unverified</li>
                    <li>Manual add adjustment</li>
                    <li>Verify action</li>
                    <li>Delete action</li>
                </ul>
                <Ledger :ledgers="ledgers" :fundId="fund.id" />
            </section>
        </div>
    </AuthenticatedLayout>
</template>
