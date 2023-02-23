<script setup>
import { computed, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import Icon from '@/Components/Icon.vue';
import moment from 'moment';
import Modal from '@/Components/Modal.vue';
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
const props = defineProps({
    campaign: Object,
    requests: Object,
    residents: Object,
});

const checkedRequests = ref([])
const checkedResidents = ref([])
const sendToResidents = ref(false);
const confirmingDeletion = ref(false);
const confirmingReminders = ref(false);
let allResidentsSelected = false
let allRequestsSelected = false
const selectedTab = ref(0)
const percentage = computed(() => {
    return parseInt((props.campaign.raised / props.campaign.target) * 100);
});
const hasRequests = computed(() => {
    if(!props.requests) {
        return false;
    }
    return Object.entries(props.requests).length;
});

function notAlreadyRequested(residentId) {
    if(props.requests[residentId]) {
        return false;
    }
    return true;
}
function selectAllRequests() {
    if(!allRequestsSelected) {
        for (const [key, value] of Object.entries(props.requests)) {
            if(!checkedRequests.value.includes(key) && !value.ledger_id) {
                checkedRequests.value.push(key);
            }
        }
        allRequestsSelected = true;
        return;
    }
    checkedRequests.value = []
    allRequestsSelected = false;

}
function selectAllResidents() {
    if(!allResidentsSelected) {
        for (const [key, value] of Object.entries(props.residents)) {
            if(!checkedResidents.value.includes(key) && notAlreadyRequested(key)) {
                checkedResidents.value.push(key);
            }
        }
        allResidentsSelected = true;
        return;
    }
    checkedResidents.value = [];
    allResidentsSelected = false;
}

const residentForm = useForm({
  amount: 50,
  members: []
})
function submitResidentForm() {
    residentForm.amount = residentForm.amount * 100;
    residentForm.members = checkedResidents.value
    residentForm.post(route('campaign.new-request',props.campaign.id),{
        onSuccess: function() {
            closeModal();
            checkedResidents.value = [];
            checkedRequests.value = [];
            changeTab(0)
        }
    })
}
const requestForm = useForm({
  members: []
})
function submitDeleteForm() {
    requestForm.members = checkedRequests.value
    requestForm.delete(route('campaign.delete-request',props.campaign.id),{
        onSuccess: function() {
            checkedRequests.value = [];
            closeModal();
        }
    })
}
function submitReminderForm() {
    requestForm.members = checkedRequests.value
    requestForm.post(route('campaign.remind-request',props.campaign.id),{
        onSuccess: function() {
            checkedRequests.value = [];
            closeModal();
        }
    })
}

function residentName(id) {
    return props.residents[id].name;
}
const closeModal = () => {
    sendToResidents.value = false;
    confirmingDeletion.value = false;
    confirmingReminders.value = false;
};
function changeTab(index) {
    selectedTab.value = index
  }
</script>

<template>
   <Head :title="campaign.description" />
    <AuthenticatedLayout>
        <template #header>
            {{  campaign.description  }}
        </template>
        <section class="mb-6">
            <div class="w-full static h-10 bg-gray-100 rounded-lg ">
                <div class="w-full fixed leading-10 pl-4 font-xl">
                    <strong>{{campaign.raised_value}}</strong> of <strong>{{props.campaign.target_value}}</strong> raised
                </div>
                <div :style="`width:${percentage}%`" class="static h-10 bg-lime-400 rounded-tl-lg rounded-bl-lg "></div>
            </div>
        </section>
        <TabGroup :selectedIndex="selectedTab" @change="changeTab">
            <TabList>
                <Tab class="ui-selected:bg-teal-700 ui-selected:text-white ui-not-selected:bg-teal-700/10 rounded-l-lg px-6 py-2 font-md ui-selected:font-semibold uppercase my-4">Active requests</Tab>
                <Tab class="ui-selected:bg-teal-700 ui-selected:text-white ui-not-selected:bg-teal-700/10 rounded-r-lg px-6 py-2 font-md ui-selected:font-semibold uppercase my-4">Send requests</Tab>
            </TabList>
            <TabPanels>
                <TabPanel>
                    <div class="mt-4 mb-10 text-right">
                        <Dropdown align="right" width="48" v-if="checkedRequests.length" >
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <PrimaryButton type="button">Action <Icon :active="true" class="ml-2" name="caret-down" /></PrimaryButton>
                                </span>
                            </template>

                            <template #content>
                                <button
                                    class="dropdown-link"
                                    @click="confirmingReminders = true">
                                    Send reminder
                                </button>
                                <button
                                    class="dropdown-link hover:bg-red-700"
                                    @click="confirmingDeletion = true">
                                    Delete
                                </button>
                            </template>
                        </Dropdown>
                    </div>
                    <table class="w-full table-auto"  v-if="hasRequests">
                        <thead>
                            <tr>
                                <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Resident</th>
                                <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Amount</th>
                                <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Sent</th>
                                <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Status</th>
                                <th class="border-b border-blue-gray-50 py-3 px-5 text-right">
                                    <SecondaryButton :type="'button'" @click="selectAllRequests">Select all</SecondaryButton>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="request in requests">
                                <td class="py-3 px-5 border-b border-blue-gray-50">
                                    {{ residentName(request.user_id) }}
                                </td>
                                <td class="py-3 px-5 border-b border-blue-gray-50">
                                    {{ request.value }}
                                </td>
                                <td class="py-3 px-5 border-b border-blue-gray-50 text-left">
                                    {{ moment(request.notified_at).format('DD/MM/YYYY') }}
                                </td>
                                <td class="py-3 px-5 border-b border-blue-gray-50 text-left">
                                    <span v-if="request.ledger"><span class="bg-lime-100 rounded-full p-2">Paid</span> {{ moment(request.ledger.created_at).format('DD/MM/YYYY') }}</span>
                                    <div v-else="request.ledger" class="">
                                        <span class="rounded-full p-2">Pending</span>
                                        <Link v-if="!request.ledger"
                                             :href="route('ledger.create',{
                                                'fund_id' : campaign.fund_id,
                                                'request_id': request.id,
                                                'amount' : request.amount / 100,
                                                'type': 'RESIDENT_OFFLINE',
                                                'user_id': request.user_id,
                                                })"
                                             class="inline-flex items-center px-2 py-1 bg-teal-700/50 border border-transparent rounded-md font-semibold text-[0.6rem] text-white uppercase tracking-widest hover:bg-teal-900/50 transition ease-in-out duration-150">
                                             Log payment
                                        </Link>
                                    </div>
                                </td>
                                <td class="py-3 px-5 border-b border-blue-gray-50 text-right">
                                    <input type="checkbox" v-if="!request.ledger_id" :value="request.user_id" v-model="checkedRequests">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p v-else>No requests sent yet</p>
                </TabPanel>
                <TabPanel>
                    <div class="mt-4 mb-10 text-right">
                        <PrimaryButton v-if="checkedResidents.length" type="button" @click="sendToResidents =true ">Send requests to selected</PrimaryButton>
                    </div>
                    <table class="w-full table-auto">
                        <thead>
                            <tr>
                                <th class="border-b border-blue-gray-50 py-3 px-5 text-left">Resident</th>
                                <th class="border-b border-blue-gray-50 py-3 px-5 text-right">
                                    <SecondaryButton :type="'button'" @click="selectAllResidents">Select all</SecondaryButton>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="resident in residents">
                                <td class="py-3 px-5 border-b border-blue-gray-50">
                                    {{ resident.name }}
                                </td>
                                <td class="py-3 px-5 border-b border-blue-gray-50 text-right">
                                    <input v-if="notAlreadyRequested(resident.id)" type="checkbox" :value="resident.id" v-model="checkedResidents" />
                                    <span v-else>Sent {{ moment(requests[resident.id].notified_at).format('DD/MM/YYYY') }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </TabPanel>
            </TabPanels>
        </TabGroup>

        <Modal :show="sendToResidents" @close="closeModal">
            <form class="p-6" @submit.prevent="submitResidentForm">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    This will send a funding request by email for the below amount to {{ checkedResidents.length }} residents
                </h2>
                <div>
                    <InputLabel for="description" value="Amount requested" />
                    <strong class="inline-block mr-2">£</strong>
                    <TextInput
                        id="amount"
                        type="number"
                        class="mt-1 inline-block "
                        v-model="residentForm.amount"
                        required
                    />
                    <InputError class="mt-2" :message="residentForm.errors.amount" />
                </div>

                <div class="mt-6 flex justify-center">
                    <SecondaryButton @click="closeModal" class="mr-10"> Cancel </SecondaryButton>
                    <PrimaryButton
                        class="ml-3"
                        :class="{ 'opacity-25': residentForm.processing }"
                        :disabled="residentForm.processing"
                    >
                        Send request
                    </PrimaryButton>

                </div>
            </form>
        </Modal>
        <Modal :show="confirmingDeletion" @close="closeModal">
            <form class="p-6" @submit.prevent="submitDeleteForm">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Are you sure you want to delete the funding requests for the selected residents?
                </h2>
                <div class="mt-6 flex justify-center">
                    <SecondaryButton @click="closeModal" class="mr-10"> Cancel </SecondaryButton>
                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': requestForm.processing }"
                        :disabled="requestForm.processing">
                        Delete requests
                    </DangerButton>
                </div>
            </form>
        </Modal>
        <Modal :show="confirmingReminders" @close="closeModal">
            <form class="p-6" @submit.prevent="submitReminderForm">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Are you sure you want to send an email reminder to the selected residents?
                </h2>
                <div class="mt-6 flex justify-center">
                    <SecondaryButton @click="closeModal" class="mr-10"> Cancel </SecondaryButton>
                    <PrimaryButton
                        class="ml-3"
                        :class="{ 'opacity-25': requestForm.processing }"
                        :disabled="requestForm.processing">
                        Send reminders
                    </PrimaryButton>
                </div>
            </form>
        </Modal>
    </AuthenticatedLayout>
</template>
