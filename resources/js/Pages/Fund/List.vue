<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    funds: Object,
});
</script>

<template>
    <Head title="Funds" />
    <AuthenticatedLayout>
        <template #header>
            Funds
        </template>
        <div class="mt-4 mb-10 text-right">
                <PrimaryButton type="button" @click="router.get(route('fund.create'))">Add fund</PrimaryButton>
        </div>

        <table v-if="funds" class="w-full table-auto">
            <thead>
                <tr>
                    <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Fund</th>
                    <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Balance</th>
                    <th class="border-b border-blue-gray-50 py-3 px-5"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="fund in funds" class="hover:bg-neutral-100">
                    <td class=" py-3 px-5 border-b border-blue-gray-50">
                        {{  fund.name }}<br>
                        <em>{{ fund.description }}</em>
                    </td>
                    <td class=" py-3 px-5 border-b border-blue-gray-50 ">{{fund.value}}</td>
                    <td class=" py-3 px-5 border-b border-blue-gray-50 text-right">
                        <Link class="inline-anchor font-bold" :href="route('fund.show', fund.id)">
                            View
                        </Link>
                        <Link class="font-light ml-3
             underline text-gray-500 hover:text-amber-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500;
                        " :href="route('fund.edit', fund.id)">
                            Edit
                        </Link>
                    </td>
                </tr>
            </tbody>
        </table>
    </AuthenticatedLayout>
</template>
