<script setup>
import { Link } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue';

defineProps({'selected' : false});
</script>

<template>
    <div class="text-left">
        <Link
            v-for="link in $page.props.nav"
            :href="route(link.route)"
            as="a"
            :class="[
                route().current() === link.route ? 'bg-amber-700/40' : '',
                'w-full block rounded-md p-4 my-2 text-white hover:bg-amber-700'
            ]"
            >
            <Icon :name="link.icon" :active="true" />
            {{ link.label }}
        </Link>
    </div>
</template>
