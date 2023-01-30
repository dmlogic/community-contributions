<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    properties: Object,
});

function newRecord() {
    router.get(route('property.create'))
}
</script>

<template>
    <Head title="Properties" />
    <AuthenticatedLayout>
        <template #header>
            Properties
        </template>

        <div class="mt-4 mb-10 text-right">
                <PrimaryButton type="button" @click="router.get(route('property.create'))">Add property</PrimaryButton>
        </div>

        <table v-if="properties" class="w-full table-auto">
            <thead>
                <tr>
                    <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Address</th>
                    <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Resident</th>
                    <th class="border-b border-blue-gray-50 py-3 px-5"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="property in properties" class="hover:bg-neutral-100">
                    <td class=" py-3 px-5 border-b border-blue-gray-50">{{  property.address }}</td>
                    <td class=" py-3 px-5 border-b border-blue-gray-50 " v-if="property.member">
                        <Link class="inline-anchor" :href="route('member.edit', property.member.id)">
                            {{  property.member.name }}
                        </Link>
                    </td>
                    <td class=" py-3 px-5 border-b border-blue-gray-50 " v-else>Unoccupied</td>
                    <td class=" py-3 px-5 border-b border-blue-gray-50 text-right">
                        <Link class="inline-anchor font-bold" :href="route('property.edit', property.id)">
                            Edit
                        </Link>
                    </td>
                </tr>
            </tbody>
        </table>
    </AuthenticatedLayout>
</template>
