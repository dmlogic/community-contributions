<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button
        :type="type"
        class="drop-shadow-lg inline-flex items-center px-4 py-2 bg-amber-700 border border-2 border-amber-900 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-900 transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
