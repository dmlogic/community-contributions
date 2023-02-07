<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <Link
        class="block w-full px-4 py-2 text-left text-sm rounded-md leading-5 text-gray-900 hover:bg-amber-700 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
    >
        <slot />
    </Link>
</template>
