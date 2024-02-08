<template>
    <div class="grid grid-cols-4 w-full py-4">
        <div class="col-span-3 flex space-x-3">
            <div class="flex items-left items-center">
                <EventIcon :event="event.type" :customIcon="customIcon" size="w-12 h-12 p-3" />
                <div>
                    <div class="text-sm text-gray-800 ml-2">{{ event.name }}</div>
                </div>
            </div>
        </div>
        <div class="flex items-right items-center text-xs text-gray-500">
            <time class="w-full text-right" :datetime="event.started_at">{{ secondsToTimeString(event.second_in_game) }}</time>
        </div>
    </div>
</template>

<script setup>

import EventIcon from '@/app/gamestats/events/EventIcon.vue';
import {secondsToTimeString} from '@/app/helpers.js';

const props = defineProps({
    customIcon: {
        type: String,
        default: null,
    },
    event: {
        type: Object,
        required: true,
    },
});

</script>
