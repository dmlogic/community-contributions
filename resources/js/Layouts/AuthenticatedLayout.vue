<script setup>
import { Link } from '@inertiajs/vue3';
import MainMenu from '@/Components/MainMenu.vue';
import BurgerMenu from '@/Components/BurgerMenu.vue';
import ProfileMenu from '@/Components/ProfileMenu.vue';
</script>


<template>
    <div>
        <div class="md:flex md:flex-col">
            <div class="md:flex md:flex-col md:h-screen">
                <div class="md:flex md:flex-shrink-0">
                    <div class="flex items-center justify-between px-6 py-4 bg-teal-900 md:flex-shrink-0 md:justify-center md:w-56">
                        <Link class="text-white text-lg font-bold">
                            {{$page.props.app_name}}
                        </Link>
                        <div class="flex md:hidden" placement="bottom-end">
                            <BurgerMenu />
                        </div>
                    </div>
                    <div class="hidden md:block flex w-full border-b">
                        <ProfileMenu />
                    </div>
                </div>
                <div class="md:flex md:flex-grow md:overflow-hidden">
                    <div class="hidden flex-shrink-0 p-4 w-56 bg-teal-800 overflow-y-auto md:block">
                        <MainMenu />
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
