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
        class="drop-shadow-lg inline-flex items-center px-4 py-2 bg-teal-700/50 border border-2 border-teal-900 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-900/50 transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
