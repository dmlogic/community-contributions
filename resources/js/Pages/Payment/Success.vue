<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <Head title="Payment form" />
    <AuthenticatedLayout>

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Payment successful</h2>
        </template>

        <div class="p-4">
            Thank you!
        </div>
    </AuthenticatedLayout>
</template>
