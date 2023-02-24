<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <Head title="Payment form" />
    <AuthenticatedLayout>

        <template #header>
            <h2 class="font-semibold text-xl leading-tight text-red-800">Payment failed</h2>
        </template>

        <div class="p-4">
            There was a problem processing your payment. Your card has not been charged.
        </div>
    </AuthenticatedLayout>
</template>
