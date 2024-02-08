<template>
    <div class="sm:overflow-hidden">
        <div class="px-4 py-5 bg-white">
            <ul role="list" class="-mb-8 bg-white divide-y divide-gray-100">
                <li v-for="event in player.events" :key="event.id">
                    <PlayerEventListItem :event="event" />
                </li>
            </ul>
        </div>
    </div>
</template>
<script setup>
import PlayerEventListItem from '@/app/gamestats/games/includes/PlayerEventListItem.vue';

const props = defineProps({
    player: {
        type: Object,
        required: true,
    },
});

</script>
