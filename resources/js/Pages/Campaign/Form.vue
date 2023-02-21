<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
const props = defineProps({
    campaign: Object,
    funds: Object
});

const form = useForm({
    description: props.campaign.description,
    fund_id: props.campaign.fund_id,
    target: props.campaign.target / 100,
})

const confirmingDeletion = ref(false);
const deleteCampaign = () => {
    form.delete(route('campaign.destroy', props.campaign.id), {
        preserveScroll: true,
        onSuccess: () => closeModal()
    });
};
const closeModal = () => {
    confirmingDeletion.value = false;
    form.reset();
};

function submitForm() {
    if(props.campaign.id) {
        form.patch(route('campaign.update', props.campaign.id));
    } else {
        form.post(route('campaign.store'));
    }
}
</script>

<template>
<Head title="Property form" />
    <AuthenticatedLayout>
        <template #header>
            <span v-if="campaign.id">
                Edit: <em class="text-gray-500">{{ campaign.description }}</em>
            </span>
            <span v-else>
                Add new campaign
            </span>
        </template>


        <form @submit.prevent="submitForm" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-6">
            <div>
                <InputLabel for="fund_id" value="Fund" />
                <SelectInput
                    id="fund_id"
                    v-model="form.fund_id">
                    <option value="">- Select -</option>
                    <option v-for="fund in funds" :value="fund.id">{{ fund.name }}</option>
                </SelectInput>

                <InputError class="mt-2" :message="form.errors.fund_id" />
            </div>
            <div>
                <InputLabel for="description" value="Description" />
                <TextInput
                    id="description"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.description"
                    required
                    autocomplete="description"
                />
                <InputError class="mt-2" :message="form.errors.street" />
            </div>
            <div>
                <InputLabel for="target" value="Target" />
                <div class="border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm  block w-full" type="text">
                    <span class="font-bold">£ </span>
                    <input
                        class="inline bg-none border-none focus:border-none"
                        id="target"
                        type="number"
                        step="1"
                        :min="valueMin"
                        v-model="form.target"
                    />
                </div>

                <InputError class="mt-2" :message="form.errors.target" />
            </div>

            <div class="mt-4 text-right">
                <PrimaryButton>{{ campaign.id ? 'Update campaign' : 'Create campaign'}}</PrimaryButton>
            </div>

        </form>

        <div v-if="campaign.id && !form.user_id" class="mt-10 p-4 sm:p-8 bg-red-900/10 w-56 shadow sm:rounded-lg space-y-6">
            <DangerButton @click="confirmingDeletion = true">Delete campaign</DangerButton>
        </div>

        <Modal :show="confirmingDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Are you sure you want to delete this campaign?
                </h2>

                <div class="mt-6 flex justify-center">
                    <SecondaryButton @click="closeModal" class="mr-10"> Cancel </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteCampaign"
                    >
                        Permanantly delete campaign
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
