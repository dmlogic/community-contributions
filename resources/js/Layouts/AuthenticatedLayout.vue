<script setup>
import { Link } from '@inertiajs/vue3';
import BurgerMenu from '@/Components/BurgerMenu.vue';
</script>


<template>
    <div>
        <div class="md:flex md:flex-col">
            <div class="md:flex md:flex-col md:h-screen">
                <div class="md:flex md:flex-shrink-0">
                    <div class="flex items-center justify-between px-6 py-4 bg-indigo-900 md:flex-shrink-0 md:justify-center md:w-56">
                        <Link class="text-white text-lg font-bold">
                            {{$page.props.app_name}}
                        </Link>
                    </div>
                    <div class="flex md:hidden" placement="bottom-end">
                        <BurgerMenu />
                    </div>
                </div>
                <div class="md:flex md:flex-grow md:overflow-hidden">
                    <div data-el="main-menu" class="hidden flex-shrink-0 p-12 w-56 bg-indigo-800 overflow-y-auto md:block">
                        Main menu 2
                    </div>
                    <div class="px-4 py-8 md:flex-1 md:p-12 md:overflow-y-auto" scroll-region>
                        <div>Flash messages here</div>
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
