<script setup>
import { Link } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue';
</script>

<template>
    <div class="text-left">
        <Link
            v-for="link in $page.props.nav"
            :href="link.href"
            as="a"
            class="w-full block rounded-md p-4 my-2 text-white hover:bg-amber-700"
            >
            <Icon :name="link.icon" active="true" />
            {{ link.label }}
        </Link>
    </div>
</template>
