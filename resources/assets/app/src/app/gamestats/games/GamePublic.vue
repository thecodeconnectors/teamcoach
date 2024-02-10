<template>
    <div class="max-w-full grid grid-cols-4 mx-auto px-4 sm:px-6 md:px-8">
        <h1 class="col-span-2 text-2xl text-gray-900">{{ title }}</h1>
        <div class="col-span-2 text-right pt-1">
            <StatusBadges :game="state.game" />
        </div>
    </div>
    <div class="max-w-full h-full mx-auto px-4 sm:px-6 md:px-8 pt-6 lg:grid lg:gap-8">
        <main>
            <div class="lg:shadow rounded-md overflow-y-auto sm:overflow-hidden">
                <ScoreBoard :game="state.game" :timersEnabled="state.timersEnabled" />
            </div>
            <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8 pt-6">
                <GameEvents v-if="state.showTab ==='events'" :game="state.game" />
                <GamePlayers v-if="state.showTab ==='players'" :playing="playing" :substitutes="substitutes" :editable="false" />
                <GameInfo v-if="state.showTab ==='game'" :game="state.game" />
            </div>
        </main>
    </div>
    <div class="absolute bottom-0 left-0 right-0 w-full grid grid-cols-12 divide-x flex-shrink-0 h-16 bg-white shadow-inner">
        <div class="col-span-3 p-3 text-center">
            <InputButton label="Players" @click="state.showTab = 'players'" class="w-full" />
        </div>
        <div class="col-span-3 p-3 text-center align-middle">
            <InputButton label="Actions" @click="state.showTab = 'events'" class="w-full" />
        </div>
        <div class="col-span-3 p-3 text-center align-middle">
            <InputButton label="Game" @click="state.showTab = 'game'" class="w-full" />
        </div>
        <div class="col-span-3 p-3 text-center align-middle">

        </div>
    </div>
</template>
<script setup>
import {useStore} from '@/framework/store/index.js';
import {useRouter} from 'vue-router';
import {showPublicGame} from '@/app/gamestats/games/games.api.js';
import {computed, reactive} from 'vue';
import StatusBadges from '@/app/gamestats/games/includes/StatusBadges.vue';
import ScoreBoard from '@/app/gamestats/games/includes/ScoreBoard.vue';
import GameEvents from '@/app/gamestats/games/includes/GameEvents.vue';
import InputButton from '@/framework/components/common/form/InputButton.vue';
import GamePlayers from '@/app/gamestats/games/includes/GamePlayers.vue';
import GameInfo from '@/app/gamestats/games/includes/GameInfo.vue';

const store = useStore();
const router = useRouter();

const title = computed(() => 'Play Game');

const props = defineProps({
    urlSecret: {
        type: String,
        required: true,
    },
});

const state = reactive({
    isLoading: false,
    showTab: 'players',
    game: {
        id: null,
        team_id: null,
        team_name: null,
        opponent_id: null,
        opponent_name: null,
        url_secret: null,

        team_points: 0,
        opponent_points: 0,

        start_at: null,
        started_at: null,
        finished_at: null,

        seconds_elapsed: 0,
        time_elapsed: '0:00',

        is_started: false,
        is_paused: false,
        is_playing: false,
        is_finished: false,
        is_public: false,

        events: [],
        players: [],
    },
});

const playing = computed(() => state.game.players.filter(player => player.type === 'playing'));
const substitutes = computed(() => state.game.players.filter(player => player.type === 'substitute'));

const getGame = async (urlSecret) => {

    const {data: game} = await showPublicGame(urlSecret);
    state.game = game;

    if (state.game.is_playing && !state.game.is_paused) {
        startTimers();
    } else {
        stopTimers();
    }
};

const startTimers = () => {
    state.timersEnabled = true;
};

const stopTimers = () => {
    state.timersEnabled = false;
};

setInterval(() => {
    getGame(props.urlSecret);
}, 10 * 1000);

getGame(props.urlSecret);
</script>
