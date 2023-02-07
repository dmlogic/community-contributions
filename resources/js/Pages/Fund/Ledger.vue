<script setup>
import LedgerEntry from '@/Components/LedgerEntry.vue';
import { ref, onMounted, reactive } from 'vue';

const props = defineProps({
    ledgers: Object,
    fundId: Number,
});
let allData = reactive(props.ledgers.data);
let nextPage = route('ledger.index',{fund_id: props.fundId});
const loadMoreIntersect = ref(null)
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
                console.log("visited")
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
    <table class="w-full table-auto">
        <thead>
            <tr>
                <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Date</th>
                <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Details</th>
                <th class="border-b border-blue-gray-50 py-3 px-5 text-right">Amount</th>
                <th class="border-b border-blue-gray-50 py-3 px-5"></th>
            </tr>
        </thead>
        <tbody>
            <LedgerEntry v-for="ledger in allData" :model="ledger" />
        </tbody>
    </table>
    <span data-foo="fum" ref="loadMoreIntersect"/>
</template>
