<template>
    <div class="px-4 py-5 bg-white">
        <ul role="list" class="-mb-8 bg-white divide-y divide-gray-100">
            <li v-for="event in game.events" :key="event.id">
                <GameEventListItem :event="event" :editable="editable" @click="editEvent" />
            </li>
        </ul>
    </div>
</template>
<script setup>
import GameEventListItem from '@/app/gamestats/games/includes/GameEventListItem.vue';
import {reactive} from 'vue';

const emit = defineEmits(['click']);

const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
    editable: {
        type: Boolean,
        default: false,
    },
});

const state = reactive({
    event: null,
});

const editEvent = (event) => {
    emit('click', event);
};

</script>
