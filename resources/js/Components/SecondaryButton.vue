<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center px-2 py-1 bg-teal-700/50 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-900/50 transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
