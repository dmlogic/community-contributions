<script setup>
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3'
defineProps({
    show: {
        type: Boolean,
        default: true,
    },
});

const message = ref(usePage().props.success)
watch(message, (msg, msgState) => {
    console.log("watching")
})
</script>

<template>
    <div v-if="show" class="items-center w-full mb-4 text-green-600">
        {{ $page.props.success }}
    </div>
</template>


