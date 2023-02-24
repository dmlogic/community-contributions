<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';

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
</script>

<template>
    <Head title="Payment form" />
    <AuthenticatedLayout>

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Confirm contribution amount</h2>
        </template>

        <div>
            <div>
                <div v-if="request">
                    <p>
                        Thank you for contributing to the {{ request.campaign.description }} funding campaign.
                    </p>
                    <p>
                        We have currently raised {{ request.campaign.raised_value }} against our target of {{ request.campaign.target_value }}.
                    </p>
                </div>
                <div v-else>
                    Thank you for making a voluntary payment.
                </div>
                <p>
                    The balance for the {{ fund.name }} fund is {{ fund.value }}
                </p>
            </div>

            <form :action="route('payment.checkout')" method="post"  v-if="!request || !request.ledger_id">
                <p>
                    Please confirm the amount to pay below and then continue to the payment form, hosted by Stripe.com
                </p>
                <p>
                    <TextInput
                        id="amount"
                        name="amount"
                        type="number"
                        v-model="form.amount"
                        class="mt-1 block w-full"
                        required
                            />
                    <input type="hidden" name="fund_id" v-model="form.fund_id"/>
                    <input type="hidden" name="request_id" v-model="form.request_id"/>
                </p>
                <p>
                    <PrimaryButton class="ml-4" >
                        Continue to payment form
                    </PrimaryButton>
                </p>
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
