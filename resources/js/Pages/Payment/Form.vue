<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, router } from '@inertiajs/inertia-vue3';


defineProps({

});


</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>

    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Payment</h2>
    </template>

    <form :action="route('payment.checkout')" method="post">
        <p>

            <TextInput
            id="amount"
            name="amount"
            type="number"
                    class="mt-1 block w-full"
                    required
                    />
        </p>
        <p>

            <button class="ml-4" >
                Proceed to checkout
            </button>
        </p>
        </form>
    </AuthenticatedLayout>
</template>
