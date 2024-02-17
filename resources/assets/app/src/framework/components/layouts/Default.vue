<template>
    <div class="h-screen flex overflow-hidden bg-white md:bg-gray-50">
        <TransitionRoot as="template" :show="sidebarOpen">
            <Dialog as="div" class="fixed inset-0 flex z-40 md:hidden" @close="sidebarOpen = false">
                <TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="transition-opacity ease-linear duration-300" leave-from="opacity-100" leave-to="opacity-0">
                    <DialogOverlay class="fixed inset-0 bg-gray-600 bg-opacity-75" />
                </TransitionChild>
                <TransitionChild as="template" enter="transition ease-in-out duration-300 transform" enter-from="-translate-x-full" enter-to="translate-x-0" leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0" leave-to="-translate-x-full">
                    <div class="relative flex-1 flex flex-col max-w-xs w-full pt-5 pb-4 bg-white">
                        <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-300" leave-from="opacity-100" leave-to="opacity-0">
                            <div class="absolute top-0 right-0 -mr-12 pt-2">
                                <button type="button" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="sidebarOpen = false">
                                    <span class="sr-only">Close sidebar</span>
                                    <Icon name="times" class="h-8 w-8 text-white" />
                                </button>
                            </div>
                        </TransitionChild>
                        <div class="flex-shrink-0 flex items-center px-4">
                            Admin
                        </div>
                        <div class="mt-5 flex-1 h-0 divide-y divide-gray-600 overflow-y-auto">
                            <nav class="px-2 space-y-1">
                                <div v-for="navigationGroup in navigationGroups" :key="navigationGroup.title" class="py-4">
                                    <router-link v-for="item in navigationGroup.navigation" :key="item.name" :to="{ name: item.route }" :class="[item.current ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
                                        <span class="h-6 w-8 text-center inline-block align-middle leading-relaxed">
                                            <Icon :name="item.icon" :class="[item.current ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 flex-shrink-0']" aria-hidden="true" />
                                        </span>
                                        {{ item.name }}
                                    </router-link>
                                </div>
                                <div class="py-4">
                                    <router-link v-for="item in bottomNavigation" :key="item.name" :to="{ name: item.route }" :class="[item.current ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
                                        <span class="h-6 w-8 text-center inline-block align-middle leading-relaxed">
                                            <Icon :name="item.icon" :class="[item.current ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 flex-shrink-0']" aria-hidden="true" />
                                        </span>
                                        {{ item.name }}
                                    </router-link>
                                </div>
                            </nav>
                        </div>
                    </div>
                </TransitionChild>
                <div class="flex-shrink-0 w-14" aria-hidden="true">
                    <!-- Dummy element to force sidebar to shrink to fit close icon -->
                </div>
            </Dialog>
        </TransitionRoot>

        <Sidebar :navigationGroups="navigationGroups" :bottomNavigation="bottomNavigation" />

        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <div class="relative z-10 flex-shrink-0 flex h-16 bg-white border-b">
                <button @click="sidebarOpen = true" type="button" class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 md:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <icon name="bars" class="text-xl" aria-hidden="true" />
                </button>
                <div class="flex-1 px-4 flex justify-between">
                    <div class="flex-1 flex items-center">
                        <AlertMessage v-if="alertMessage" :message="alertMessage" color="green" />
                    </div>
                    <div class="ml-4 flex items-center md:ml-6">
                        <button @click="notificationSidebarOpen = true" type="button" class="bg-white p-2 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">View notifications</span>
                            <Icon name="bell" class="h-6 w-6 mt-3" />
                            <span v-if="notificationCount" class="block w-2 h-2 transform translate-x-2 -translate-y-6 bg-red-600 rounded-full"></span>
                        </button>
                        <ProfileMenu />
                    </div>
                </div>
            </div>

            <main class="flex-1 relative overflow-y-auto focus:outline-none">
                <div class="pt-6 md:pb-6 h-full">
                    <router-view></router-view>
                </div>
            </main>
            <Notifications :open="notificationSidebarOpen" v-on:close="notificationSidebarOpen = false" />
        </div>
    </div>
</template>
<script setup>
import {computed, ref} from 'vue';
import {useStore} from '@/framework/store';
import Sidebar from './Sidebar.vue';
import {Dialog, DialogOverlay, TransitionChild, TransitionRoot,} from '@headlessui/vue';
import {useRouter} from 'vue-router';
import Notifications from '@/framework/components/common/notifications/Notifications.vue';
import Icon from '@/framework/components/common/icon/Icon.vue';
import AlertMessage from '@/framework/components/common/alerts/AlertMessage.vue';
import ProfileMenu from '@/framework/components/layouts/includes/ProfileMenu.vue';

const store = useStore();
const sidebarOpen = ref(false);
const notificationSidebarOpen = ref(false);
const router = useRouter();
const fullPath = computed(() => router.currentRoute.value.fullPath);
const notificationCount = computed(() => store.notifications?.length);
const alertMessage = computed(() => store.alertMessage);

let navigationGroups = computed(() => {
    let groups = [
        {
            title: '',
            navigation: [
                {name: 'Dashboard', route: 'home', icon: 'home', current: fullPath.value === '/'},
            ]
        },
        {
            title: '',
            navigation: [
                {name: 'Teams', route: 'teams', icon: 'users', current: fullPath.value.startsWith('/teams')},
                {name: 'Players', route: 'players', icon: 'user', current: fullPath.value.startsWith('/players')},
                {name: 'Games', route: 'games', icon: 'soccer-ball', current: fullPath.value.startsWith('/games')},
                {name: 'Training', route: 'training', icon: 'chalkboard-user', current: fullPath.value.startsWith('/training')},
            ]
        },
    ];

    return groups;
});

const bottomNavigation = computed(() => [
    {name: 'Users', route: 'users', icon: 'users', current: fullPath.value.startsWith('/users')},
    {name: 'Settings', route: 'settings', icon: 'cog', current: fullPath.value.startsWith('/settings')},
]);

if (import.meta.env.VUE_APP_PUSHER_APP_KEY?.length) {
    window.Echo.channel('messages').listen('MessageBroadcasted', (e) => {
        store.setAlertMessage(e.message);
    });
}

</script>
