<template>
    <div class="relative pb-8">
        <span v-if="showConnector" class="absolute left-3 top-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
        <div class="relative flex space-x-3">
            <div>
                <EventIcon :event="eventType" :customIcon="customIcon" />
            </div>
            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                <div>
                    <p class="text-sm text-gray-500">{{ text ? text : eventType }}</p>
                </div>
                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                    <time :datetime="dateTime">{{ timeElapsed }}</time>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import EventIcon from '@/app/gamestats/events/EventIcon.vue';

const props = defineProps({
    customIcon: {
        type: String,
        default: null,
    },
    eventType: {
        type: String,
        default: '',
    },
    text: {
        type: String,
        default: null,
    },
    dateTime: {
        type: String,
        default: '',
    },
    timeElapsed: {
        type: String,
        default: '',
    },
    showConnector: {
        type: Boolean,
        default: true,
    },
});

</script>
