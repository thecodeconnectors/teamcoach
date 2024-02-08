<template>
    <div class="sm:overflow-hidden">
        <div class="px-4 py-5 bg-white">
            <ul role="list" class="-mb-8 bg-white divide-y divide-gray-100">
                <li v-for="event in game.events" :key="event.id">
                    <GameEventListItem :event="event" />
                </li>
            </ul>
        </div>
    </div>
</template>
<script setup>
import GameEventListItem from '@/app/gamestats/events/GameEventListItem.vue';

const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
});

</script>
