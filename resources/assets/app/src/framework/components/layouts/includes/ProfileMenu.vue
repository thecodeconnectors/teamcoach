<template>
    <Menu as="div" class="ml-6 relative">
        <div>
            <MenuButton class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <span class="sr-only">Open user menu</span>
                <img v-if="user" class="h-8 w-8 rounded-full" :src="user.profile_picture" :alt="user.name" />
            </MenuButton>
        </div>
        <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
            <MenuItems class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                <MenuItem v-for="item in userNavigation" :key="item.name" v-slot="{ active }">
                    <router-link :to="item.route" :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">{{ item.name }}</router-link>
                </MenuItem>

                <span @click="onClearCache" class="w-full block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                    Clear Cache
                </span>

                <a :href="frontendUrl" target="_blank" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1">Frontend</a>
            </MenuItems>
        </transition>
    </Menu>
</template>

<script>
import {Menu, MenuButton, MenuItem, MenuItems} from '@headlessui/vue';
import {useStore} from '@/framework/store';
import {computed} from 'vue';
import {clearCache} from '@/framework/components/cache/cache.api';

export default {
    name: 'ProfileMenu',
    components: {
        Menu,
        MenuButton,
        MenuItem,
        MenuItems,
    },
    setup() {
        const store = useStore();
        const user = computed(() => store.user);
        const frontendUrl = computed(() => '//' + store.frontendDomain);

        const userNavigation = computed(() => [
            {name: 'Your Profile', route: {name: 'users.edit', params: {id: user.value.id}}},
            {name: 'Sign out', route: {name: 'auth.logout'}},
        ]);

        const onClearCache = async function () {
            await clearCache();
            await store.addToastMessage({title: 'Cache cleared'});
        };

        return {
            user,
            userNavigation,
            frontendUrl,
            onClearCache,
        };
    },
};
</script>
