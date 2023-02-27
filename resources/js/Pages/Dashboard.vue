<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { balanceBackground, balanceColor } from '@/helpers.js';
const props = defineProps({
    funds: Object,
    reconcile: Object,
});

function percentage(campaign) {
    let result = parseInt((campaign.raised / campaign.target) * 100);
    return result > 100 ? 100 : result;
}
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <div class="">
            <section v-for="fund in funds" class="shadow sm:rounded-lg space-y-6 p-4 sm:p-8 mb-6">
                <div class="grid md:grid-cols-4">
                    <div class="col-span-3">
                        <h1 class="text-2xl font-black mb-3">{{ fund.name  }}</h1>
                        <p class="font-light">{{ fund.description }}</p>
                    </div>
                    <div class="">
                        <div :class="['my-6 p-2 text-center text-2xl     rounded-md', balanceBackground(fund.balance)]">
                            <span :class="['font-light pr-2',balanceColor(fund.balance)]">Balance:</span> <span class="font-bold">{{ fund.value }}</span>
                        </div>
                        <PrimaryButton type="button" class="" @click="
                            router.get(route('payment.form',{
                                'fund_id': fund.id
                            }))">Make additional payment</PrimaryButton>
                    </div>
                </div>

                <div v-for="campaign in fund.campaigns">
                    <h2 class="font-bold text-xl my-3">{{ campaign.description }}</h2>
                    <div v-if="campaign.requests.length" class="my-6">
                        <div v-if="campaign.requests[0].ledger_id">
                            <div v-if="campaign.requests[0].ledger.verified_at" class="border border-lime-100 bg-lime-100 text-lime-800 p-4 rounded text-2xl inline-block">You're all paid up, thank you!</div>
                            <div v-else  class="border border-teal-100 bg-teal-100 text-teal-800 p-4 rounded text-2xl inline-block">Payment confirmation pending</div>
                        </div>
                        <div v-else class="border border-red-100 bg-red-100 p-4 rounded text-2xl inline-block">
                            You owe <strong class="text-red-500">{{ campaign.requests[0].value }}</strong>
                            <PrimaryButton type="button" class="mx-6" @click="
                            router.get(route('payment.form',{
                                'request_id': campaign.requests[0].id,
                                'fund_id': campaign.fund_id
                            }))">Pay now</PrimaryButton>
                            <SecondaryButton type="button" class="mx-6" @click="
                            router.get(route('payment.offline-form',{
                                'request_id': campaign.requests[0].id,
                                'fund_id': campaign.fund_id
                            }))">I've already paid</SecondaryButton>
                        </div>
                    </div>
                    <div v-else class="mb-6 font-light">
                        No payment is requested of you for this campaign
                    </div>
                    <div class="w-full static h-10 bg-gray-200 rounded-lg ">
                        <div class="w-full fixed leading-10 pl-4 font-xl">
                            <strong>{{ campaign.raised_value }}</strong> of <strong>{{ campaign.target_value }}</strong> raised
                        </div>
                        <div :style="`width:${percentage(campaign)}%`" class="static h-10 bg-lime-400 rounded-lg "></div>
                    </div>
                </div>
            </section>

            <section v-if="reconcile.length" class="shadow sm:rounded-lg space-y-6 p-4 sm:p-8">
                <h1 class="text-2xl font-black mb-3">Account reconcile needed</h1>
                <p class="">The are offline payments awaiting confirmation for the following funds:</p>
                <div v-for="recon in reconcile" class="">
                    <Link class="inline-anchor text-2xl" :href="route('fund.show',{'fund': recon.id, 'filter': 'unverified'})">{{ recon.name }}</Link>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
