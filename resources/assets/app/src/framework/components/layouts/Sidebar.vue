<template>
    <div class="hidden md:flex md:flex-shrink-0">
        <div class="flex flex-col w-64">
            <div class="flex flex-col flex-grow border-r border-gray-200 pt-5 pb-4 bg-white overflow-y-auto">
                <div class="flex items-center justify-center">
                    <img width="80" height="80" class="logo h-10 -mt-1 w-auto hidden md:block" :src="store.logo" />
                </div>
                <div class="mt-5 flex-grow flex flex-col ">
                    <nav class="flex-1 px-2 bg-white divide-y divide-gray-200">
                        <div v-for="navigationGroup in navigationGroups" :key="navigationGroup.title" class="py-4">
                            <router-link v-for="item in navigationGroup.navigation" :key="item.name" :to="{ name: item.route }" :class="[item.current ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
                            <span class="h-6 w-8 text-center inline-block align-middle leading-relaxed">
                                <Icon :name="item.icon" :class="[item.current ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 flex-shrink-0']" aria-hidden="true" />
                            </span>
                                {{ item.name }}
                            </router-link>
                        </div>
                    </nav>
                </div>

                <div class="flex-shrink-0 flex border-t border-gray-200 py-4">
                    <nav class="flex-1 px-2 bg-white space-y-1">
                        <router-link v-for="item in bottomNavigation" :key="item.name" :to="{ name: item.route }" :class="[item.current ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
                            <span class="h-6 w-8 text-center inline-block align-middle leading-relaxed">
                                <Icon :name="item.icon" :class="[item.current ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 flex-shrink-0']" aria-hidden="true" />
                            </span>
                            {{ item.name }}
                        </router-link>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {computed} from 'vue';
import {useRouter} from 'vue-router';
import {useStore} from '@/framework/store';
import Icon from '@/framework/components/common/icon/Icon.vue';

const store = useStore();
const router = useRouter();

const props = defineProps({
    navigationGroups: {
        type: Array,
        default: [],
    },
});

const fullPath = computed(() => router.currentRoute.value.fullPath);

const bottomNavigation = computed(() => [
    {name: 'Users', route: 'users', icon: 'users', current: fullPath.value.startsWith('/users')},
    {name: 'Settings', route: 'settings', icon: 'cog', current: fullPath.value.startsWith('/settings')},
]);

</script>
