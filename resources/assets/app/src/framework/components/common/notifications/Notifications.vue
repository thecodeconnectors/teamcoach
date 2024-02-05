<template>
    <SlideOver title="Notifications" :open="open" v-on:close="close" v-if="notifications && notifications.length">
        <div class="mt-6">
            <nav class="grid gap-y-8">
                <a v-for="notification in notifications" :key="notification.data.title" :href="notification.data.link" target="_blank" class="-m-3 p-3 flex items-center rounded-md hover:bg-gray-50">
                    <span class="ml-3 text-base font-medium text-gray-900">
                        {{ notification.data.title }}
                    </span>
                    <span class="ml-3 text-xs font-normal text-gray-600">
                        {{ notification.data.subTitle }}
                    </span>
                </a>
            </nav>
        </div>
    </SlideOver>
</template>

<script setup>
import {computed, onMounted} from 'vue';
import {useStore} from '@/framework/store';
import SlideOver from '@/framework/components/common/modals/SlideOver.vue';

const emit = defineEmits(['close']);

const props = defineProps({
    open: {
        type: Boolean,
        default: false,
    },
});

const store = useStore();

onMounted(() => {
    // getNotifications().then(function (notifications) {
    //     store.setNotifications(notifications);
    // });
});

const notifications = computed(() => store.notifications);

const close = () => {
    emit('close');
};
</script>
