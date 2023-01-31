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
    property: Object,
    residents: Object,
});

const form = useForm({
    number: props.property.number,
    street: props.property.street,
    town: props.property.town,
    postcode: props.property.postcode,
    user_id: props.property.user_id,
})

const confirmingDeletion = ref(false);
const deleteProperty = () => {
    form.delete(route('property.destroy', props.property.id), {
        preserveScroll: true,
        onSuccess: () => closeModal()
    });
};

const closeModal = () => {
    confirmingDeletion.value = false;
    form.reset();
};

function submitForm() {
    if(props.property.id) {
        form.patch(route('property.update', props.property.id));
    } else {
        form.post(route('property.store'));
    }
}
</script>

<template>
    <Head title="Property form" />
    <AuthenticatedLayout>
        <template #header>
            <span v-if="property.id">
                Edit: <em class="text-gray-500">{{ property.address }}</em>
            </span>
            <span v-else>
                Add new property
            </span>
        </template>


        <form @submit.prevent="submitForm" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-6">

            <div>
                <InputLabel for="number" value="Number" />
                <TextInput
                    id="number"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.number"
                    required
                    autocomplete="number"
                />
                <InputError class="mt-2" :message="form.errors.number" />
            </div>
            <div>
                <InputLabel for="street" value="Street" />
                <TextInput
                    id="street"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.street"
                    required
                    autocomplete="street"
                />
                <InputError class="mt-2" :message="form.errors.street" />
            </div>
            <div>
                <InputLabel for="town" value="Town" />
                <TextInput
                    id="town"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.town"
                    required
                    autocomplete="town"
                />
                <InputError class="mt-2" :message="form.errors.town" />
            </div>
            <div>
                <InputLabel for="postcode" value="Postcode" />
                <TextInput
                    id="postcode"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.postcode"
                    required
                    autocomplete="postcode"
                />
                <InputError class="mt-2" :message="form.errors.postcode" />
            </div>
            <div>
                <InputLabel for="user_id" value="Resident" />
                <SelectInput
                    id="user_id"
                    v-model="form.user_id">
                    <option value="">- Unoccupied -</option>
                    <option v-for="resident in residents" :value="resident.id">{{ resident.name }}</option>
                </SelectInput>

                <InputError class="mt-2" :message="form.errors.user_id" />
            </div>

            <div class="mt-4 text-right">
                <PrimaryButton>{{ property.id ? 'Update property' : 'Create property'}}</PrimaryButton>
            </div>

        </form>

        <div v-if="property.id && !form.user_id" class="mt-10 p-4 sm:p-8 bg-red-900/10 w-56 shadow sm:rounded-lg space-y-6">
            <DangerButton @click="confirmingDeletion = true">Delete property</DangerButton>
        </div>

        <Modal :show="confirmingDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Are you sure you want to delete this property?
                </h2>

                <div class="mt-6 flex justify-center">
                    <SecondaryButton @click="closeModal" class="mr-10"> Cancel </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteProperty"
                    >
                        Permanantly delete property
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
