<template>
    <span v-if="!game.is_started" class="text-xs tracking-widest py-1">
        Starts at {{ formatDate(game.start_at) }}
    </span>
    <span v-if="game.is_playing" class="uppercase text-xs tracking-widest text-white bg-red-500 py-1 px-3 rounded-full">
        <span class="bg-white rounded-full w-2 h-2 inline-block"></span> Live
    </span>
    <span v-if="game.is_paused" class="uppercase text-xs tracking-widest text-white bg-green-500 py-1 px-3 rounded-full ml-3">
                <span class="bg-white rounded-full w-2 h-2 inline-block"></span> Paused
            </span>
    <span v-if="game.is_finished" class="uppercase text-xs tracking-widest text-white bg-blue-500 py-1 px-3 rounded-full ml-3">
        <span class="bg-white rounded-full w-2 h-2 inline-block"></span> Finished
    </span>
</template>
<script setup>
import {formatDate} from '@/framework/helpers.js';

const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
});
</script>
