<script setup>
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import { Link } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue';
</script>

<template>
    <div
    style="position: fixed; top: 0; right: 0;  bottom: 0; "
    class="w-56 h-10 text-right p-6">
        <Menu as="div" class="relative inline-block text-left">
            <div>
                <MenuButton
                class="inline-flex w-full justify-center px-4 py-2">
                    <Icon name="user" />
                    {{ $page.props.auth.user.name }}
                    <Icon name="chevron-down" class="ml-1" />
                </MenuButton>
            </div>
            <transition enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-in" leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0">
                <MenuItems
                    class="absolute right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div class="px-1 py-1">
                        <MenuItem v-slot="{ active }" >
                            <Link
                                :href="route('profile.edit')"
                                as="a"
                                :class="[
                                    active ? 'bg-amber-700 text-white' : 'text-gray-900',
                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]"
                                >Edit your details</Link>
                        </MenuItem>
                        <MenuItem v-slot="{ active }" >
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                :class="[
                                    active ? 'bg-amber-700 text-white' : 'text-gray-900',
                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]"
                                type="button">Logout</Link>
                        </MenuItem>
                    </div>
                </MenuItems>
            </transition>
        </Menu>
    </div>
</template>
