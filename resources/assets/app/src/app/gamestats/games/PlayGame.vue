<template>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">{{ title }}</h1>
    </div>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8 pt-6 lg:grid lg:grid-cols-12 lg:gap-8">
        <main class="col-span-12">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
                <div class="pb-6 bg-white space-y-6">
                    <div class="grid grid-cols-12 w-full align-middle text-white bg-gray-600">
                        <div class="col-span-4 p-3 flex items-center justify-center font-bold text-xl">
                            {{ state.game.team_name }}
                        </div>
                        <div class="col-span-4 p-3 text-center bg-gray-800 text-white">
                            <span class="font-bold text-2xl">{{ state.game.team_points }}</span>
                            <span class="px-3">-</span>
                            <span class="font-bold text-2xl">{{ state.game.opponent_points }}</span>
                        </div>
                        <div class="col-span-4 p-3 flex items-center justify-center text-right font-bold text-xl">
                            {{ state.game.opponent_name }}
                        </div>
                    </div>
                    <div class="grid grid-cols-12 w-full align-middle">
                        <div class="col-span-12 text-center">
                            <span class="w-full text-white bg-blue-500 shadow rounded-2xl py-3 px-6">
                                {{ state.game.time_elapsed }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                    <div class="sm:col-span-6">
                        <table class="w-full">
                            <thead>
                            <tr>
                                <th class="text-left">Players</th>
                                <th class="text-right">Playtime</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="player in state.game.playing" :key="player.id" :value="player.id">
                                <td>
                                    <span class="w-full flex items-left items-center">
                                        <img :src="player.profile_picture" :alt="player.name" width="32" class="mr-3 bg-blue-600 border-white border-2 rounded-full shadow" />
                                        <span>{{ player.name }}</span>
                                    </span>
                                </td>
                                <td class="text-right">
                                    {{ player.playtime }}"
                                </td>
                                <td class="text-right object-right">
                                    <Icon name="refresh" @click="openSubstiteMenu(player)" size="sm" class="rounded-full border-blue-600 text-blue-600 border-2 shadow-md p-1 mr-3" />
                                    <Icon name="chart-line" @click="openPlayerMenu(player)" size="sm" class="rounded-full border-blue-600 text-blue-600 border-2 shadow-md p-1" />
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-left">Substitutes</th>
                            </tr>
                            <tr v-for="player in state.game.substitutes" :key="player.id" :value="player.id">
                                <td>
                                    <span class="w-full flex items-left items-center">
                                        <img :src="player.profile_picture" :alt="player.name" width="32" class="mr-3 bg-blue-600 border-white border-2 rounded-full shadow-md" />
                                        <span>{{ player.name }}</span>
                                    </span>
                                </td>
                                <td class="text-right">
                                    {{ player.playtime }}"
                                </td>
                                <td class="text-right object-right">
                                    <Icon name="chart-line" @click="openPlayerMenu(player)" size="sm" class="rounded-full border-blue-600 text-blue-600 border-2 shadow-md p-1" />
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <Confirm
        @confirm="start"
        @cancel="state.showConfirmStart = false"
        :open="state.showConfirmStart"
        title="Start game"
        text="Are you sure you want to start the game?"
        confirmButtonText="Start game!"
    />
    <Confirm
        @confirm="pause"
        @cancel="state.showConfirmPause = false"
        :open="state.showConfirmPause"
        title="Pause game"
        text="Are you sure you want to pause the game?"
        confirmButtonText="Pause game!"
    />
    <Confirm
        @confirm="resume"
        @cancel="state.showConfirmResume = false"
        :open="state.showConfirmResume"
        title="Resume game"
        text="Are you sure you want to resume the game?"
        confirmButtonText="Resume game!"
    />
    <Confirm
        @confirm="finish"
        @cancel="state.showConfirmFinish = false"
        :open="state.showConfirmFinish"
        title="Finish game"
        text="Are you sure you want to finish the game?"
        confirmButtonText="Finish game!"
    />
    <SlideOver :title="`Substitute player ${state.substitutePlayer?.name}`"
               :open="state.substitutePlayer !== null"
               @close="state.substitutePlayer = null;state.newPlayer = null"
               width-class="w-screen max-w-2xl">
        <div class="shadow sm:rounded-md sm:overflow-hidden">
            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                <div class="mb-3 font-bold">Select new player:</div>
                <template v-for="player in state.game.substitutes" :key="player.id">
                    <button
                        :class="['border rounded mb-2 p-2 w-full flex items-center justify-between', state.newPlayer?.id === player.id ? 'bg-blue-500 text-white shadow' : '']"
                        @click="state.newPlayer = player">
                        <span>{{ player.name }} - <i class="text-xs">{{ player.position }}</i></span>
                        <Icon v-if="state.newPlayer?.id === player.id" prefix="far" name="check-circle" class="h-4 w-4 text-white" />
                    </button>
                </template>
            </div>
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 flex justify-between">
                <span></span>
                <InputButton :disabled="state.newPlayer === null" label="Switch players" @click="switchPlayers" />
            </div>
        </div>
    </SlideOver>
    <SlideOver :title="`Player ${state.focusPlayer?.name}`"
               :open="state.focusPlayer !== null"
               @close="state.focusPlayer = null"
               width-class="w-screen max-w-2xl">
        <div class="sm:overflow-hidden">
            <div class="px-4 py-5 bg-white">
                <ul role="list" class="-mb-8">
                    <li v-for="(event, eventIdx) in state.focusPlayer?.events" :key="event.id">
                        <EventListItem
                            :eventType="event.type"
                            :text="`${event.name}`"
                            :dateTime="event.started_at"
                            :timeElapsed="event.time_elapsed"
                            :showConnector="eventIdx !== state.focusPlayer?.events.length - 1"
                        />
                    </li>
                </ul>
            </div>
            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                <template v-for="playerActionEventType in state.playerActionEventTypes" :key="playerActionEventType.id">
                    <button
                        @click="state.focusPlayer.playerEvent = playerActionEventType.id"
                        :class="['border rounded mb-2 p-2 w-full flex items-center justify-between', state.focusPlayer?.playerEvent === playerActionEventType.id ? 'bg-blue-500 text-white shadow' : '']"
                    >
                        <span class="flex items-center justify-between">
                            <EventIcon :event="playerActionEventType.id" />
                            <span class="ml-2">{{ playerActionEventType.name }}</span>
                        </span>
                        <Icon v-if="state.focusPlayer?.playerEvent === playerActionEventType.id" prefix="far" name="check-circle" class="h-4 w-4 text-white" />
                    </button>
                </template>
            </div>
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 flex justify-between">
                <span></span>
                <InputButton label="Add action" @click="addPlayerEvent" :disabled="!state.focusPlayer?.playerEvent" />
            </div>
            <div class="absolute bottom-5 left-1/2 z-20 transform -translate-x-1/2">
                <button type="button" @click="state.showAddGameEvent = true">
                    <Icon class="h-16 w-16 text-green-600" name="circle-plus" />
                </button>
            </div>
        </div>
    </SlideOver>
    <SlideOver title="Game events"
               :open="state.showGameEvents"
               @close="state.showGameEvents = false"
               width-class="w-screen max-w-2xl">
        <div class="sm:overflow-hidden">
            <div class="px-4 py-5 bg-white">
                <ul role="list" class="-mb-8">
                    <li>
                        <EventListItem
                            text="Game started"
                            customIcon="circle-play"
                            :dateTime="state.game.started_at"
                            timeElapsed="0:00"
                            :showConnector="true"
                        />
                    </li>
                    <li v-for="(event, eventIdx) in state.game?.events" :key="event.id">
                        <EventListItem
                            :eventType="event.type"
                            :text="`${event.name} ${event.player_name ? event.player_name : ''}`"
                            :dateTime="event.started_at"
                            :timeElapsed="event.time_elapsed"
                            :showConnector="eventIdx !== state.game?.events.length - 1"
                        />
                    </li>
                    <li>
                        <EventListItem
                            v-if="state.game.finished_at"
                            text="Game finished"
                            customIcon="flag-checkered"
                            :dateTime="state.game.finished_at"
                            :timeElapsed="state.game.time_elapsed"
                            :showConnector="false"
                        />
                    </li>
                </ul>
            </div>
        </div>
    </SlideOver>

    <div class="absolute bottom-0 w-full grid grid-cols-12 divide-x z-10 flex-shrink-0 h-16 bg-white shadow">
        <div class="col-span-3 p-3 text-center">
            <Icon class="h-8 w-8 " name="user" />
        </div>
        <div class="col-span-3 p-3 text-center align-middle">
            <InputButton v-if="!state.game.is_started" label="Start" @click="state.showConfirmStart = true" class="w-full" />
            <InputButton v-if="state.game.is_started && !state.game.is_finished" label="Finish" color="red" @click="state.showConfirmFinish = true" class="w-full" />
        </div>
        <div class="col-span-3 p-3 text-center align-middle">
            <InputButton v-if="state.game.is_started && !state.game.is_paused && !state.game.is_finished" label="Pause" @click="state.showConfirmPause = true" class="w-full" />
            <InputButton v-if="state.game.is_paused && !state.game.is_finished" label="Resume" @click="state.showConfirmResume = true" class="w-full" />
        </div>
        <div class="col-span-3 p-3 text-center align-middle">
            <button type="button" @click="state.showGameEvents = true">
                <Icon class="h-8 w-8 " name="chart-line" />
            </button>
        </div>
    </div>
</template>
<script setup>
import {finishGame, getGamePlay, getGamePlayerTypes, pauseGame, resumeGame, startGame, swtichPlayers} from './games.api.js';
import {useRouter} from 'vue-router';
import {useStore} from '@/framework/store';
import Confirm from '@/framework/components/common/modals/Confirm.vue';
import InputButton from '@/framework/components/common/form/InputButton.vue';
import {computed, onMounted, reactive} from 'vue';
import {getPositions} from '@/app/gamestats/positions/positions.api.js';
import Icon from '@/framework/components/common/icon/Icon.vue';
import SlideOver from '@/framework/components/common/modals/SlideOver.vue';
import {getPlayerActionEventTypes} from '@/app/gamestats/player-action-event-types/player-action-event-types.api.js';
import {storeEvent} from '@/app/gamestats/events/events.api.js';
import EventIcon from '@/app/gamestats/events/EventIcon.vue';
import EventListItem from '@/app/gamestats/events/EventListItem.vue';

const store = useStore();
const router = useRouter();

const props = defineProps({
    id: {
        type: [Number, String],
        default: null,
    },
});

const state = reactive({
    isLoading: false,
    showConfirmStart: false,
    showConfirmFinish: false,
    showConfirmPause: false,
    showConfirmResume: false,
    showAddGameEvent: false,
    showGameEvents: false,
    substitutePlayer: null,
    newPlayer: null,
    focusPlayer: null,
    positions: [],
    gamePlayerTypes: [],
    playerActionEventTypes: [],
    game: {
        id: null,
        team_id: null,
        team_name: null,
        opponent_id: null,
        opponent_name: null,

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

        playing: [],
        substitutes: [],
    },
});

const title = computed(() => 'Play Game');

const loadPositions = async () => {
    const {data: positions} = await getPositions();
    state.positions = positions;
};

const loadGamePlayerTypes = async () => {
    const {data: gamePlayerTypes} = await getGamePlayerTypes();
    state.gamePlayerTypes = gamePlayerTypes;
};

const loadPlayerActionEventTypes = async () => {
    const {data: playerActionEventTypes} = await getPlayerActionEventTypes();
    state.playerActionEventTypes = playerActionEventTypes;
};

const getGame = async (id) => {
    state.isLoading = true;
    const {data: game} = await getGamePlay(id);
    state.game = game;
    state.isLoading = false;
    startTimer();
};

const start = async () => {
    await control(startGame, 'Game started', 'showConfirmStart');
    startTimer();
};

const pause = async () => {
    await control(pauseGame, 'Game paused', 'showConfirmPause');
    stopTimer();
};

const resume = async () => {
    await control(resumeGame, 'Game resumed', 'showConfirmResume');
    startTimer();
};

const finish = async () => {
    await control(finishGame, 'Game finished', 'showConfirmFinish');
    stopTimer();
};

const control = async (method, message, confirmDialog) => {
    const {data: game} = await method(state.game.id);
    state.game = reactive(game);
    store.addToastMessage({title: message});
    state[confirmDialog] = false;
};

const openSubstiteMenu = (player) => {
    state.substitutePlayer = player;
};

const openPlayerMenu = (player) => {
    state.focusPlayer = player;
};

const switchPlayers = async () => {
    const {data: game} = await swtichPlayers(state.game.id, state.newPlayer.id, state.substitutePlayer.id);
    state.game = game;
    store.addToastMessage({title: `Player ${state.substitutePlayer.name} replaced with ${state.newPlayer.name}`});
    state.substitutePlayer = null;
    state.newPlayer = null;
};

const addPlayerEvent = async () => {
    const {data: event} = await storeEvent({
        player_id: state.focusPlayer.id,
        team_id: state.game.team_id,
        game_id: state.game.id,
        type: state.focusPlayer?.playerEvent,
    });
    store.addToastMessage({title: 'Player action added'});
    // todo reload events
};

let timer = null;

const startTimer = () => {
    const d = new Date();
    d.setHours(0);
    d.setMinutes(0);
    d.setSeconds(state.game.seconds_elapsed, 0);
    timer = setInterval(function () {
        if (state.game.is_playing && !state.game.is_paused) {
            const hours = d.getHours() < 10 ? '0' + d.getHours() : d.getHours();
            const minutes = d.getMinutes() < 10 ? '0' + d.getMinutes() : d.getMinutes();
            const seconds = d.getSeconds() < 10 ? '0' + d.getSeconds() : d.getSeconds();
            state.game.time_elapsed = hours + ':' + minutes + ':' + seconds;
        }
        d.setTime(d.getTime() + 500);
    }, 500);
};

const stopTimer = () => {
    clearInterval(timer);
};

onMounted(() => {
    getGame(props.id);
    loadPositions();
    loadGamePlayerTypes();
    loadPlayerActionEventTypes();
});
</script>
