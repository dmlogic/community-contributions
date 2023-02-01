<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { balanceBackground } from '@/helpers.js';
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
                <tr v-for="fund in funds"
                    class="hover:bg-neutral-100 cursor-pointer"

                    @click="router.get(route('fund.show',fund.id))">
                    <td class=" py-3 px-5 border-b border-blue-gray-50">
                        {{  fund.name }}<br>
                        <em>{{ fund.description }}</em>
                    </td>
                    <td class="py-3 px-5 border-b border-blue-gray-50">
                        <span :class="['rounded-md p-2',balanceBackground(fund.balance)]">
                            {{fund.value}}
                        </span>
                    </td>
                    <td class=" py-3 px-5 border-b border-blue-gray-50 text-right">
                        <Link class="inline-anchor font-bold" @click.stop="" :href="route('fund.edit', fund.id)">
                            Edit
                        </Link>
                    </td>
                </tr>
            </tbody>
        </table>
    </AuthenticatedLayout>
</template>
