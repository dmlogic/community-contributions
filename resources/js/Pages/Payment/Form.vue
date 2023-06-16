<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    request: Object,
    fund: Object,
    amount: Number
});

const form = useForm({
    amount: props.amount,
    fund_id: props.fund.id,
    request_id: props.request ? props.request.id : null
})

function submitForm() {
    form.post(route('payment.checkout'));
}
</script>

<template>
    <Head title="Payment form" />
    <AuthenticatedLayout>

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Confirm contribution amount</h2>
        </template>

        <div class="p-4">
            <div v-if="request">
                <p class="mb-4">
                    Thank you for contributing to the <strong>{{ request.campaign.description }}</strong> funding campaign.
                </p>
                <p class="mb-4">
                    We have currently raised <strong>{{ request.campaign.raised_value }}</strong> against our target of <strong>{{ request.campaign.target_value }}</strong>.
                </p>
            </div>
            <div v-else class="mb-4">
                Thank you for making a voluntary payment.
            </div>
            <p class="mb-4">
                The balance for the <strong>{{ fund.name }}</strong> fund is <strong>{{ fund.value }}</strong>
            </p>

            <form @submit.prevent="submitForm"  v-if="!request || !request.ledger_id"  class="mt-8">
                <p class="mb-4">
                    Please confirm the amount to pay below and then continue to the payment form, hosted by Stripe.com
                </p>
                <div class="mb-4">
                    <div class="border border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm inline-block text-2xl">
                        <span class="font-bold px-2">£ </span>
                        <input
                            class="inline bg-none border-none focus:border-none text-2xl"
                            name="amount"
                            type="number"
                            step="1"
                            min="1"
                            v-model="form.amount"
                        />
                    </div>
                    <input type="hidden" name="fund_id" v-model="form.fund_id"/>
                    <input type="hidden" name="request_id" v-model="form.request_id"/>
                </div>
                <div class="mt-4 text-right">
                    <PrimaryButton class="ml-4" >
                        Continue to payment form
                    </PrimaryButton>
                </div>
            </form>
            <div v-else>
                A payment against this funding request has already been made.
                If you would like to make an additional contribution, you can
                make a <Link :href="route('payment.form',{
                    fund_id: fund.id
                })" class="inline-anchor">One-off payment</Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
