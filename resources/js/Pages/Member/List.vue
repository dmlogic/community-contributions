<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'

import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    members: Object,
});

</script>

<template>
    <Head title="Members" />
    <AuthenticatedLayout>
        <template #header>
            Community Members
        </template>

        <div class="mt-4 mb-10 text-right">
            <PrimaryButton type="button" class="mr-4" @click="router.get(route('invitation.create'))">Invite new resident</PrimaryButton>
            <PrimaryButton type="button" @click="router.get(route('member.create'))">Manually add member</PrimaryButton>
        </div>
        <table v-if="members" class="w-full table-auto">
            <thead>
                <tr>
                    <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Name</th>
                    <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Role(s)</th>
                    <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Resident at</th>
                    <th class="border-b border-blue-gray-50 py-3 px-5"></th>
                </tr>
                <tr v-for="member in members" class="hover:bg-neutral-100">
                    <td class=" py-3 px-5 border-b border-blue-gray-50">{{  member.name }}</td>
                    <td class=" py-3 px-5 border-b border-blue-gray-50">
                        {{ member.roles.map((x) => { return x.name }).join(", ") }}
                    </td>
                    <td class=" py-3 px-5 border-b border-blue-gray-50">
                        <Link v-if="member.property" class="inline-anchor" :href="route('property.edit', member.property.id)">
                            {{  member.property.address }}
                        </Link>
                        <span v-else>n/a</span>
                    </td>
                    <td class=" py-3 px-5 border-b border-blue-gray-50 text-right">
                        <Link class="inline-anchor font-bold" :href="route('member.edit', member.id)">
                            Edit
                        </Link>
                    </td>
                </tr>
            </thead>
        </table>
    </AuthenticatedLayout>
</template>
