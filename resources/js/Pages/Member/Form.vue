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
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    member: Object,
    roles: Object,
});
const confirmingDeletion = ref(false);
const deleteMember = () => {
    form.delete(route('member.destroy', props.member.id), {
        preserveScroll: true,
        onSuccess: () => closeModal()
    });
};

const closeModal = () => {
    confirmingDeletion.value = false;
    form.reset();
};
const form = useForm({
    name: props.member.name,
    email: props.member.email,
    role_id: props.member.roles.map((r) => { return r.id }),
})

function submitForm() {
    if(props.member.id) {
        form.patch(route('member.update', props.member.id));
    } else {
        form.post(route('member.store'));
    }
}
</script>

<template>
    <Head title="Member form" />
    <AuthenticatedLayout>
        <template #header>
            <span v-if="member.id">
                Edit: <em class="text-gray-500">{{ member.name }}</em>
            </span>
            <span v-else>
                Add new member
            </span>
        </template>

        <div v-if="member.property" class="text-lg my-4 p-4 rounded-md bg-teal-800/10">
            Resident at
            <Link class="inline-anchor" :href="route('property.edit',member.property.id )">
                {{  member.property.address }}
            </Link>
        </div>
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
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="email"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>
            <div>
                <InputLabel value="Roles" />
                <div v-for="role in roles">
                    <label class="block my-4 italic">
                        <input
                            type="checkbox"
                            v-model="form.role_id"
                            :value="role.value"
                            class="rounded dark:bg-gray-900 border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500 p-2 mr-2 "
                        />
                        {{ role.label }}
                    </label>
                </div>
            </div>

            <div class="mt-4 text-right">
                <PrimaryButton>{{ member.id ? 'Update member' : 'Create member'}}</PrimaryButton>
            </div>
        </form>

        <div v-if="member.id && !member.property && member.id != $page.props.auth.user.id" class="mt-10 p-4 sm:p-8 bg-red-900/10 w-56 shadow sm:rounded-lg space-y-6">
            <DangerButton @click="confirmingDeletion = true">Delete member</DangerButton>
        </div>

        <Modal :show="confirmingDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Are you sure you want to delete this member?
                </h2>

                <div class="mt-6 flex justify-center">
                    <SecondaryButton @click="closeModal" class="mr-10"> Cancel </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteMember"
                    >
                        Delete member
                    </DangerButton>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
