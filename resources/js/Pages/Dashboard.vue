<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <ul>
            <li>Access requests (admin)</li>
            <li>Requests waiting payment</li>
            <li>Fund balance graph</li>
            <li>Latest activity</li>
        </ul>
    </AuthenticatedLayout>
</template>
