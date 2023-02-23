<script setup>
import moment from 'moment';
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { balanceBackground } from '@/helpers.js';
import Icon from '@/Components/Icon.vue';
import Dropdown from '@/Components/Dropdown.vue';

const props = defineProps({
    model: Object,
})

const date = computed(() => moment(props.model.created_at).format('DD/MM/YYYY'))

const type = computed(function () {
    switch (props.model.type) {
        case 'RESIDENT_REQUEST':
            return 'Funding request';
        case 'RESIDENT_ADDITIONAL':
            return 'Voluntary payment';
        case 'RESIDENT_OFFLINE':
            return 'Offline payment';
        case 'ADMIN_ADJUSTMENT':
            return 'Ledger adjustment';
        case 'EXPENDITURE':
            return 'Expenditure';
        case 'FEES':
            return 'Banking fees';
    }
})
</script>

<template>
    <tr class="hover:bg-neutral-100 align-top">
        <td class=" py-3 px-5 border-b border-blue-gray-50">
            {{ date }}
        </td>
        <td class="py-3 px-5 border-b border-blue-gray-50">
            <p class="font-bold mb-2 uppercase text-sm text-gray-400">{{ type }}</p>
            <p v-if="model.description" class="mb-2">{{ model.description }}</p>
            <p v-if="model.user" class="mb-2">
                <Link class="inline-anchor" :href="route('member.show', props.model.user_id)">
                {{ model.user.name }}
                </Link>
            </p>
        </td>
        <td class="py-3 px-5 border-b border-blue-gray-50 text-right">
            <span v-if="props.model.verified_at"
                :class="['p-2 rounded-full', balanceBackground(props.model.amount)]">
                {{ props.model.value }}
            </span>
            <div v-else>
                <span class="p-2 block rounded-md">
                    {{ props.model.value }}
                </span>
                <span class="p-2 block rounded-md bg-blue-500/20 uppercase">Unverified</span>
            </div>
        </td>
        <td class=" py-3 px-5 border-b border-blue-gray-50 text-right">
            <Dropdown align="right" width="48">
                <template #trigger>
                    <span class="inline-flex rounded-md">
                        <button type="button"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <Icon name="ellipsis" />
                        </button>
                    </span>
                </template>

                <template #content>
                    <button
                        class="dropdown-link"
                        v-if="!model.verified_at"
                        @click="$emit('verifyLedger', model.id)">
                        Verify
                    </button>
                    <button
                        class="dropdown-link"
                        @click="$emit('deleteLedger', model.id)">
                        Delete
                    </button>
                </template>
            </Dropdown>
        </td>
    </tr>
</template>
