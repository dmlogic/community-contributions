<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    campaigns: Object,
});
</script>

<template>
    <Head title="Campaigns" />
    <AuthenticatedLayout>
        <template #header>
            Campaigns
        </template>

        <div class="mt-4 mb-10 text-right">
            <PrimaryButton type="button" @click="router.get(route('campaign.create'))">Add campaign</PrimaryButton>
        </div>
        <table v-if="campaigns.length" class="w-full table-auto">
            <thead>
                <tr>
                    <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Description</th>
                    <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Status</th>
                    <th class="border-b border-blue-gray-50 py-3 px-5"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="campaign in campaigns"
                    class="hover:bg-neutral-100 cursor-pointer"
                    @click="router.get(route('campaign.show',campaign.id))">
                    <td class=" py-3 px-5 border-b border-blue-gray-50">{{  campaign.description }}</td>
                    <td class=" py-3 px-5 border-b border-blue-gray-50">
                        <span v-if="campaign.closed_at" class="font-xs p-2 rounded-full bg-yellow-600/40">Closed</span>
                        {{  campaign.raised_value }} / {{  campaign.target_value }}
                    </td>
                    <td class=" py-3 px-5 border-b border-blue-gray-50 text-right">
                        <Link class="inline-anchor font-bold" @click.stop="" :href="route('campaign.edit', campaign.id)">
                            Edit
                        </Link>
                    </td>
                </tr>
            </tbody>
        </table>
    </AuthenticatedLayout>
</template>
