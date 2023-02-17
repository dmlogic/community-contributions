<script setup>
import { Link } from '@inertiajs/vue3';
import LedgerEntry from '@/Components/LedgerEntry.vue';
import { ref, onMounted, reactive, computed } from 'vue';

const props = defineProps({
    ledgers: Object,
    fundId: Number,
});
let allData = reactive(props.ledgers.data);
let nextPage = props.ledgers.next_page_url;
const loadMoreIntersect = ref(null)
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
onMounted(() => {
    const observer = new IntersectionObserver(entries => entries.forEach(entry => entry.isIntersecting && loadMore(), {
      rootMargin: "-150px 0px 0px 0px"
    }));
    observer.observe(loadMoreIntersect.value)
})
</script>

<template>
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
            <LedgerEntry v-for="ledger in allData" :model="ledger"  />
        </tbody>
    </table>
    <span data-foo="fum" ref="loadMoreIntersect"/>
</template>
