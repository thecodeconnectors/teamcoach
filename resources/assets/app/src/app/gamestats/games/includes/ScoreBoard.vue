<template>
    <div class="pb-6 bg-white space-y-6">
        <div class="grid grid-cols-12 w-full align-middle text-white bg-blue-500 rounded-md lg:rounded-none">
            <div class="col-span-4 p-3 flex items-center justify-center text-xl">
                {{ game.is_away_game ? game.opponent_name : game.team_name }}
            </div>
            <div class="col-span-4 p-3 text-center bg-blue-600 text-white">
                <span class="text-2xl">{{ game.is_away_game ? opponentPoints : teamPoints }}</span>
                <span class="px-3">-</span>
                <span class="text-2xl">{{ game.is_away_game ? teamPoints : opponentPoints }}</span>
            </div>
            <div class="col-span-4 p-3 flex items-center justify-center text-right text-xl">
                {{ game.is_away_game ? game.team_name : game.opponent_name }}
            </div>
        </div>
        <div class="grid grid-cols-12 w-full align-middle">
            <div class="col-span-12 text-center">
                <span class="w-full text-white bg-blue-500 shadow rounded-md py-3 px-6">
                    <LiveSecondsToTimeString :enabled="timersEnabled" :seconds="game.played_seconds" />
                </span>
            </div>
        </div>
    </div>
</template>
<script setup>
import LiveSecondsToTimeString from '@/app/gamestats/LiveSecondsToTimeString.vue';
import {computed} from 'vue';

const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
    timersEnabled: {
        type: Boolean,
        default: false,
    },
});

const teamPoints = computed(() => props.game.events.filter(event => event.type === 'goal')?.length);
const opponentPoints = computed(() => props.game.events.filter(event => event.type === 'goal-lossed')?.length);

</script>
