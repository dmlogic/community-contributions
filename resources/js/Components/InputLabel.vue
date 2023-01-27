<script setup>
defineProps(['value']);
</script>

<template>
    <label class="block my-2">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
