<script setup>
import moment from 'moment';
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { balanceBackground } from '@/helpers.js';

const props = defineProps({
    ledger: Object,
})

const date =computed(() => moment(props.ledger.created_at).format('DD/MM/YYYY'))

const  type = computed(function() {
    switch(props.ledger.type) {
        case 'RESIDENT_REQUEST':
            return 'Fundung request';
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
            {{  date }}
        </td>
        <td class="py-3 px-5 border-b border-blue-gray-50">
            <p class="font-bold mb-2 uppercase text-sm text-gray-400">{{ type }}</p>
            <p v-if="ledger.description" class="mb-2">{{ ledger.description }}</p>
            <p v-if="ledger.user" class="mb-2">
                <Link class="inline-anchor" :href="route('member.show', props.ledger.user_id)">
                    {{ ledger.user.name }}
                </Link>
            </p>
        </td>
        <td class="py-3 px-5 border-b border-blue-gray-50 text-right">
            <span v-if="props.ledger.verified_at" :class="['p-2 block rounded-md', balanceBackground(props.ledger.amount)]">
                {{props.ledger.value}}
            </span>
            <div v-else>
                <span class="p-2 block rounded-md">
                    {{props.ledger.value}}
                </span>
                <span class="p-2 block rounded-md bg-blue-500/20 uppercase">Unverified</span>
            </div>
        </td>
        <td class=" py-3 px-5 border-b border-blue-gray-50 text-right">
            @actions
        </td>
    </tr>
</template>
