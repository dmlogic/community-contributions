<script setup>
import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectInput from '@/Components/SelectInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ledgerTypes } from '@/helpers.js';

const props = defineProps({
    residents: Object,
    fund: Object,
    requestId: String,
    type: String,
    created: String,
});

const form = useForm({
    user_id: null,
    fund_id: props.fund.id,
    request_id: props.requestId,
    type: props.type,
    description: null,
    amount: 0,
    created_at: props.created,
})

const valueClass = computed(() => {
    return isNegativeType() || isNegativeValue() ? 'bg-red-100' : 'bg-lime-100'
});

const valueSymbol = computed(() => {
    if(isNegativeValue()) {
        return '';
    }
    return isNegativeType() ? '-' : '+'
});

const valueDescription = computed(() => {
    return isNegativeType() || isNegativeValue() ? 'deduction' : 'addition'
});

const valueMin = computed(() => {
     return form.type === 'ADMIN_ADJUSTMENT' ? '' : '0.1';
});

function isNegativeType() {
    return ['EXPENDITURE', 'FEES'].includes(form.type);
}
function isNegativeValue() {
    return form.type === 'ADMIN_ADJUSTMENT' && form.amount < 0
}

function checkAmountSymbol() {
    if(form.amount >= 0) {
        return;
    }
    if(form.type !== 'ADMIN_ADJUSTMENT') {
        form.amount = form.amount * -1
    }
}
function submitForm() {
    form.post(route('ledger.store'));
}
</script>

<template>
    <datalist id="commonPayments">
        <option value="50"></option>
        <option value="100"></option>
        <option value="200"></option>
    </datalist>
   <Head title="Add manual transaction" />
    <AuthenticatedLayout>
        <template #header>
            New transaction for {{ fund.name }}
        </template>
        <form @submit.prevent="submitForm" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-6">

            <div>
                <InputLabel for="type" value="Type" />
                <SelectInput
                    id="type"
                    required
                    @change="checkAmountSymbol()"
                    v-model="form.type">
                    <option v-for="desc, val in ledgerTypes()" :value="val">{{ desc }}</option>
                </SelectInput>
                <InputError class="mt-2" :message="form.errors.type" />
            </div>
            <div>
                <InputLabel for="created_at" value="Transaction date" />
                <TextInput
                    id="created_at"
                    type="datetime-local"
                    class="mt-1 block w-full"
                    v-model="form.created_at"
                />
                <InputError class="mt-2" :message="form.errors.created_at" />
            </div>
            <div>
                <InputLabel for="description" value="Description" />
                <TextInput
                    id="description"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.description"
                    placeholder="Optional description"
                    autocomplete="description"
                />
                <InputError class="mt-2" :message="form.errors.description" />
            </div>
            <div>
                <InputLabel for="amount" value="Value" />
                <div :class="['rounded p-2', valueClass]">
                    <strong class="text-lg mr-2">{{ valueSymbol }}</strong>
                    <TextInput
                        id="amount"
                        type="number"
                        :min="valueMin"
                        list="commonPayments"
                        class="mt-1"
                        required
                        v-model="form.amount"
                    />
                    <strong class="text-lg font-light ml-2">{{ valueDescription }}</strong>
                </div>
                <InputError class="mt-2" :message="form.amount.description" />
            </div>
            <div>
                <InputLabel for="user_id" value="Resident" />
                <SelectInput
                    id="user_id"
                    v-model="form.user_id">
                    <option value="">- select -</option>
                    <option v-for="resident in residents" :value="resident.id">{{ resident.name }}</option>
                </SelectInput>

                <InputError class="mt-2" :message="form.errors.user_id" />
            </div>

            <div class="mt-4 text-right">
                <PrimaryButton>Create transaction</PrimaryButton>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
