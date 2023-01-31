<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head,  useForm } from '@inertiajs/vue3';

const props = defineProps({
    invitation: Object
});

const form = useForm({
    code: props.code,
    password: null,
    password_confirmation: null,
})

</script>

<template>
    <GuestLayout>
        <Head title="Invitation" />

        <form @submit.prevent="form.post(route('invitation.confirm',invitation.code))" class="">
            <h1 class="text-xl font-bold text-teal-700 text-center">
                Almost there!
            </h1>
            <p class="my-4 leading-normal ">
                Set a password below for the next time you login. Click the button to confirm.
            </p>

            <div class="my-6">
                <div>
                    <InputLabel for="password" value="Password" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>
                <div>
                    <InputLabel for="password_confirmation" value="Confirm password" />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>
            </div>
            <div class="my-6 text-center">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Accept invite and login
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
