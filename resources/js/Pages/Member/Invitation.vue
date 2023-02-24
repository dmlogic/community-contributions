<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    properties: Object,
    roles: Object,
});

const form = useForm({
    name: '',
    email: '',
    property_id: ''
})
</script>

<template>
    <Head title="Invitation form" />
    <AuthenticatedLayout>
        <template #header>
            Invite new resident
        </template>
        <form @submit.prevent="form.post(route('invitation.store'))"  class="standard-form">
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
                <InputLabel for="user_id" value="Property" />
                <SelectInput
                    id="property_id"
                    v-model="form.property_id">
                    <option value="">- None -</option>
                    <option v-for="property in properties" :value="property.id">
                        {{ property.member ? `REPLACE ${property.member.name} at:` : '' }}
                        {{ property.address }}
                    </option>
                </SelectInput>

                <InputError class="mt-2" :message="form.errors.user_id" />
            </div>
            <div class="mt-4 text-right">
                <PrimaryButton>Send invitation</PrimaryButton>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
