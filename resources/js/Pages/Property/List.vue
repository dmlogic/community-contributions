<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    properties: Object,
});
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        listing
    </AuthenticatedLayout>
</template>
