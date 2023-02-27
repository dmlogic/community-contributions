<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    request: Object,
    fund: Object,
    paymentDate: Object,
});

const form = useForm({
    fund_id: props.fund.id,
    request_id: props.request.id,
    payment_date: props.paymentDate
})
</script>

<template>
    <Head title="Payment form" />
    <AuthenticatedLayout>

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Confirm payment by bank transfer</h2>
        </template>

        <div class="p-4">
            <p class="mb-4">
                Thank you for contributing to the <strong>{{ request.value }}</strong> to the <strong>{{ request.campaign.description }}</strong> funding campaign.
            </p>
            <form @submit.prevent="form.post(route('payment.offline'))">
                <p class="mb-4">
                    Please confirm the date on which payment was made
                </p>
                <div class="mb-4">
                    <InputLabel for="payment_date" value="Transaction date" />
                    <TextInput
                        id="payment_date"
                        type="date"
                        class="mt-1"
                        v-model="form.payment_date"
                    />
                    <InputError class="mt-2" :message="form.errors.payment_date" />
                </div>
                <div class="mt-4 text-right">
                    <PrimaryButton class="ml-4" >
                        Confirm payment
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
