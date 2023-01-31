<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    fund: Object,
});

const form = useForm({
    name: props.fund.name,
    description: props.fund.description,
})
const confirmingDeletion = ref(false);
const deleteFund = () => {
    form.delete(route('fund.destroy', props.fund.id), {
        preserveScroll: true,
        onSuccess: () => closeModal()
    });
};

const closeModal = () => {
    confirmingDeletion.value = false;
    form.reset();
};

function submitForm() {
    if(props.fund.id) {
        form.patch(route('fund.update', props.fund.id));
    } else {
        form.post(route('fund.store'));
    }
}
</script>

<template>
   <Head title="Fund form" />
    <AuthenticatedLayout>
        <template #header>
            <span v-if="fund.id">
                Edit: <em class="text-gray-500">{{ fund.name }}</em>
            </span>
            <span v-else>
                Add new fund
            </span>
        </template>


        <form @submit.prevent="submitForm" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-6">

            <div>
                <InputLabel for="name" value="Name" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
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
                <InputError class="mt-2" :message="form.errors.description" />
            </div>

            <div class="mt-4 text-right">
                <PrimaryButton>{{ fund.id ? 'Update fund' : 'Create fund'}}</PrimaryButton>
            </div>

        </form>

        <div v-if="fund.id" class="mt-10 p-4 sm:p-8 bg-red-900/10 w-56 shadow sm:rounded-lg space-y-6">
            <DangerButton @click="confirmingDeletion = true">Delete fund</DangerButton>
        </div>

        <Modal :show="confirmingDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Are you sure you want to delete this fund?
                </h2>

                <div class="mt-6 flex justify-center">
                    <SecondaryButton @click="closeModal" class="mr-10"> Cancel </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteFund"
                    >
                        Permanantly delete fund
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
