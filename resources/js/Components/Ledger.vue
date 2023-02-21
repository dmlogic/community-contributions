<script setup>
import { router, Link } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import LedgerEntry from '@/Components/LedgerEntry.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { ref, onMounted, reactive, computed } from 'vue';

const props = defineProps({
    ledgers: Object,
    fundId: Number,
});
let allData = reactive(props.ledgers.data);
let nextPage = props.ledgers.next_page_url;
const loadMoreIntersect = ref(null)
const confirmingVerify = ref(false);
const confirmingDeletion = ref(false);
const showAddForm = ref(false);
const formProcesssing = ref(false);
const formAction = ref(false);
const filter = computed(function() {
    let urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('filter') ?? 'all'
});

function loadMore() {
    if (nextPage === null) {
        return
    }
    fetch(nextPage,{
        credentials: "same-origin",
        method: "GET"
    })
    .then(response => response.json())
    .then((response) => {
        Object.assign(allData, [...allData, ...response.ledgers.data])
        nextPage = response.ledgers.next_page_url
    });
}

function confirmDeleteLedger(entryId) {
    formAction.value = route('ledger.destroy', entryId);
    confirmingDeletion.value = true;
}

function confirmVerifyLedger(entryId) {
    formAction.value = route('ledger.verify', entryId);
    confirmingVerify.value = true;
}

const closeModal = () => {
    confirmingDeletion.value = false;
    confirmingVerify.value = false
};

onMounted(() => {
    const observer = new IntersectionObserver(entries => entries.forEach(entry => entry.isIntersecting && loadMore(), {
      rootMargin: "-150px 0px 0px 0px"
    }));
    observer.observe(loadMoreIntersect.value)
})
</script>

<template>
    <div class="mt-4 mb-10 text-right">
        <PrimaryButton type="button" @click="router.get(route('ledger.create',{fund_id: props.fundId}))">Add manual entry</PrimaryButton>
    </div>
    <div class="my-4">
        <Link :class="[
            'mr-4 inline-flex items-center px-2 py-1 bg-teal-700/50 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-900/50 transition ease-in-out duration-150',
            filter === 'all' || !filter ? 'bg-teal-900' : null
            ]"
             :href="route('fund.show',props.fundId)">All activity</Link>
        <Link :class="[
            'mr-4 inline-flex items-center px-2 py-1 bg-teal-700/50 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-900/50 transition ease-in-out duration-150',
            filter === 'unverified' ? 'bg-blue-900' : null
            ]"
            :href="route('fund.show',{fund: props.fundId, filter: 'unverified'})">Unverified payments</Link>
    </div>
    <table class="w-full table-auto" v-if="ledgers.data.length">
        <thead>
            <tr>
                <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Date</th>
                <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Details</th>
                <th class="border-b border-blue-gray-50 py-3 px-5 text-right">Amount</th>
                <th class="border-b border-blue-gray-50 py-3 px-5"></th>
            </tr>
        </thead>
        <tbody>
            <LedgerEntry
                v-for="ledger in allData"
                :model="ledger"
                @verify-ledger="confirmVerifyLedger"
                @delete-ledger="confirmDeleteLedger"  />
        </tbody>
    </table>
    <span data-foo="fum" ref="loadMoreIntersect"/>

    <Modal :show="confirmingDeletion" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Are you sure you want to delete this transaction? There is no undo and the fund balance will be adjusted.
            </h2>

            <div class="mt-6 flex justify-center">
                <SecondaryButton @click="closeModal" class="mr-10"> Cancel </SecondaryButton>
                <form :action="formAction" method="POST">
                    <DangerButton
                        class="ml-3"
                        :type="'submit'"
                        :class="{ 'opacity-25': formProcesssing }"
                        :disabled="formProcesssing"
                        @click="formProcesssing.value = true">
                        Delete transaction
                    </DangerButton>
                    <input type="hidden" name="_method" value="DELETE"/>
                </form>
            </div>
        </div>
    </Modal>
    <Modal :show="confirmingVerify" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Are you sure you want to vefify this transaction and update fund balance?
            </h2>

            <div class="mt-6 flex justify-center">
                <SecondaryButton @click="closeModal" class="mr-10"> Cancel </SecondaryButton>
                <form :action="formAction" method="POST">
                    <PrimaryButton
                        class="ml-3"
                        :type="'submit'"
                        :class="{ 'opacity-25': formProcesssing }"
                        :disabled="formProcesssing"
                        @click="formProcesssing.value = true">
                        Verify transaction
                    </PrimaryButton>
                    <input type="hidden" name="_method" value="PATCH"/>
                </form>
            </div>
        </div>
    </Modal>
</template>
