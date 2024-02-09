<template>
    <div class="max-w-full grid grid-cols-4 mx-auto px-4 sm:px-6 md:px-8">
        <h1 class="col-span-2 text-2xl text-gray-900">{{ title }}</h1>
        <div class="col-span-2 text-right pt-1">
            <StatusBadges :game="state.game" />
            <span v-if="state.game.is_public" class="text-xs tracking-widest py-1">
                <button @click="openPublicGame" class="p-2">
                    <Icon name="external-link" />
                </button>
            </span>
        </div>
    </div>
    <div class="max-w-full h-full mx-auto px-4 sm:px-6 md:px-8 pt-6 lg:grid lg:gap-8">
        <main>
            <div class="lg:shadow rounded-md overflow-y-auto sm:overflow-hidden">
                <ScoreBoard :game="state.game" :timersEnabled="state.timersEnabled" />
                <div class="bg-white space-y-6 lg:p-6">
                    <div class="sm:col-span-6">
                        <GamePlayers
                            :playing="playing"
                            :substitutes="substitutes"
                            :editable="true"
                            @open-substite-menu="openSubstiteMenu"
                            @open-player-event-list="openPlayerEventList"
                            @open-player-menu="openPlayerMenu"
                        />
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 w-full grid grid-cols-12 divide-x flex-shrink-0 h-16 bg-white shadow-inner">
                <div class="col-span-3 p-3 text-center">
                    <InputButton v-if="!state.game.is_public" label="Publish" @click="state.showConfirmPublish = true" color="green" class="w-full" />
                    <InputButton v-if="state.game.is_public" label="Unpublish" @click="state.showConfirmUnpublish = true" color="green" class="w-full" />
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
                        <Icon class="h-8 w-8 " name="chart-simple" />
                    </button>
                </div>
            </div>
        </main>
    </div>
    <SlideOver :title="`Substitute ${state.substitutePlayer?.name}`"
               :open="state.substitutePlayer !== null"
               @close="state.substitutePlayer = null;state.newPlayer = null"
               width-class="w-screen max-w-2xl">
        <div class="sm:overflow-hidden">
            <div class="bg-white space-y-6">
                <div class="mb-3 font-bold">Select new player:</div>
                <template v-for="player in substitutes" :key="player.id">
                    <button
                        :class="['border rounded mb-2 p-2 w-full flex items-center justify-between', state.newPlayer?.id === player.id ? 'bg-blue-500 text-white shadow' : '']"
                        @click="state.newPlayer = player">
                       <span class="flex items-center justify-between">
                            <ProfilePicture :src="player.profile_picture" :alt="player.name" width="28" />
                        <span class="ml-1 mr-2">{{ player.name }} - <i class="text-xs">{{ player.position }}</i></span>
                       </span>
                        <Icon v-if="state.newPlayer?.id === player.id" prefix="far" name="check-circle" class="h-4 w-4 text-white" />
                        <span v-else></span>
                    </button>
                </template>

                <InputButton :disabled="state.newPlayer === null" label="Switch players" class="py-3 w-full" @click="switchPlayers" />
            </div>
        </div>
    </SlideOver>
    <SlideOver :title="`Player ${state.focusPlayer?.name}`"
               :open="state.focusPlayer !== null"
               @close="state.focusPlayer = null"
               width-class="w-screen max-w-2xl">
        <div class="sm:overflow-hidden">
            <div class="bg-white space-y-3">
                <template v-for="playerActionEventType in state.playerActionEventTypes" :key="playerActionEventType.id">
                    <button
                        @click="toggleFocusPlayerEvent(playerActionEventType)"
                        :class="['border rounded mb-2 p-2 w-full flex items-center justify-between', state.focusPlayer?.playerEvent === playerActionEventType.id ? 'bg-blue-500 text-white shadow' : '']"
                    >
                        <span class="flex items-center justify-between">
                            <EventIcon :event="playerActionEventType.id" />
                            <span class="ml-2">{{ playerActionEventType.name }}</span>
                        </span>
                        <Icon v-if="state.focusPlayer?.playerEvent === playerActionEventType.id" prefix="far" name="check-circle" class="h-4 w-4 text-white" />
                    </button>
                </template>
                <InputButton label="Add action" class="py-3 w-full" @click="addPlayerEvent" :disabled="!state.focusPlayer?.playerEvent || state.isLoading" />
            </div>
        </div>
    </SlideOver>
    <SlideOver title="Game events"
               :open="state.showGameEvents"
               @close="state.showGameEvents = false"
               width-class="w-screen max-w-2xl">
        <GameEvents :game="state.game" :editable="true" @click="editEvent" />
    </SlideOver>
    <SlideOver title="Edit action"
               :open="state.editEvent !== null"
               @close="state.editEvent = null"
               width-class="w-screen max-w-2xl">
        <template v-if="state.editEvent">
            <div class="sm:overflow-hidden">
                <div class="bg-white space-y-6">
                    <DropDownSelect
                        label="Player"
                        v-if="state.game.players && state.editEvent.player_id"
                        v-model="state.editEvent.player_id"
                        :options="state.game.players"
                    />
                    <DropDownSelect
                        label="Event"
                        v-if="state.playerActionEventTypes.length"
                        v-model="state.editEvent.type"
                        :options="state.playerActionEventTypes"
                    />
                    <InputField type="datetime-local" id="start_at" v-model="state.editEvent.started_at" label="Time" />
                </div>
                <div class="space-y-6 py-6">
                    <InputButton label="Update action" class="w-full" @click="updateEvent" :disabled="state.isLoading" />
                    <InputButton label="Delete action" class="w-full" color="red" @click="state.showConfirmDeleteEvent = true" :disabled="state.isLoading" />
                </div>
            </div>
        </template>
    </SlideOver>
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
    <Confirm
        @confirm="publish"
        @cancel="state.showConfirmPublish = false"
        :open="state.showConfirmPublish"
        title="Publish game"
        text="Are you sure you want to publish the game?"
        confirmButtonText="Publish game!"
    />
    <Confirm
        @confirm="unpublish"
        @cancel="state.showConfirmUnpublish = false"
        :open="state.showConfirmUnpublish"
        title="Unpublish game"
        text="Are you sure you want to unpublish the game?"
        confirmButtonText="Unpublish game!"
    />
    <Confirm
        @confirm="deleteEvent"
        @cancel="state.showConfirmDeleteEvent = false"
        :open="state.showConfirmDeleteEvent"
        title="Delete action"
        text="Are you sure you want to delete this action?"
        confirmButtonText="Delete action!"
    />
</template>
<script setup>
import {finishGame, getGamePlay, getGamePlayerTypes, pauseGame, publishGame, resumeGame, startGame, swtichPlayers, unpublishGame} from './games.api.js';
import {useRouter} from 'vue-router';
import {useStore} from '@/framework/store';
import Confirm from '@/framework/components/common/modals/Confirm.vue';
import InputButton from '@/framework/components/common/form/InputButton.vue';
import {computed, reactive} from 'vue';
import {getPositions} from '@/app/gamestats/positions/positions.api.js';
import Icon from '@/framework/components/common/icon/Icon.vue';
import SlideOver from '@/framework/components/common/modals/SlideOver.vue';
import {getPlayerActionEventTypes} from '@/app/gamestats/player-action-event-types/player-action-event-types.api.js';
import {destroyEvent, patchEvent, storeEvent} from '@/app/gamestats/events/events.api.js';
import EventIcon from '@/app/gamestats/events/EventIcon.vue';
import ProfilePicture from '@/app/gamestats/players/ProfilePicture.vue';
import StatusBadges from '@/app/gamestats/games/includes/StatusBadges.vue';
import ScoreBoard from '@/app/gamestats/games/includes/ScoreBoard.vue';
import GameEvents from '@/app/gamestats/games/includes/GameEvents.vue';
import DropDownSelect from '@/framework/components/common/form/DropDownSelect.vue';
import InputField from '@/framework/components/common/form/InputField.vue';
import GamePlayers from '@/app/gamestats/games/includes/GamePlayers.vue';

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
    timersEnabled: false,
    showConfirmStart: false,
    showConfirmFinish: false,
    showConfirmPause: false,
    showConfirmResume: false,
    showAddGameEvent: false,
    showGameEvents: false,
    showConfirmPublish: false,
    showConfirmUnpublish: false,
    showConfirmDeleteEvent: false,
    substitutePlayer: null,
    newPlayer: null,
    focusPlayer: null,
    editEvent: null,
    positions: [],
    gamePlayerTypes: [],
    playerActionEventTypes: [],
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

const title = computed(() => 'Play Game');
const playing = computed(() => state.game.players.filter(player => player.type === 'playing'));
const substitutes = computed(() => state.game.players.filter(player => player.type === 'substitute'));

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
    if (state.game.is_playing && !state.game.is_paused) {
        startTimers();
    }
};

const start = async () => {
    await control(startGame, 'Game started', 'showConfirmStart');
    startTimers();
};

const pause = async () => {
    await control(pauseGame, 'Game paused', 'showConfirmPause');
    stopTimers();
};

const resume = async () => {
    await control(resumeGame, 'Game resumed', 'showConfirmResume');
    startTimers();
};

const finish = async () => {
    await control(finishGame, 'Game finished', 'showConfirmFinish');
    stopTimers();
};

const publish = async () => {
    state.isLoading = true;
    const {data: game} = await publishGame(state.game.id);
    state.game = game;
    state.isLoading = false;
    state.showConfirmPublish = false;
    store.addToastMessage({title: 'Game published'});
};

const unpublish = async () => {
    state.isLoading = true;
    const {data: game} = await unpublishGame(state.game.id);
    state.game = game;
    state.isLoading = false;
    state.showConfirmUnpublish = false;
    store.addToastMessage({title: 'Game Unpublished'});
};

const control = async (method, message, confirmDialog) => {
    const {data: game} = await method(state.game.id);
    state.game = reactive(game);
    store.addToastMessage({title: message});
    state[confirmDialog] = false;
};

const openPublicGame = () => {
    const url = `/games/public/${state.game.url_secret}`;
    window.open(url, '_blank');
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

const toggleFocusPlayerEvent = (eventType) => {
    if (state.focusPlayer.playerEvent === eventType.id) {
        state.focusPlayer.playerEvent = null;
    } else {
        state.focusPlayer.playerEvent = eventType.id;
    }
};

const addPlayerEvent = async () => {
    state.isLoading = true;
    const {data: event} = await storeEvent({
        player_id: state.focusPlayer.id,
        team_id: state.game.team_id,
        game_id: state.game.id,
        type: state.focusPlayer?.playerEvent,
    });

    pushEvent(event);

    state.isLoading = false;
    state.focusPlayer = null;
    store.addToastMessage({title: 'Player action added'});
};

const updateEvent = async () => {
    state.isLoading = true;
    const {data: event} = await patchEvent(state.editEvent.id, state.editEvent);
    pushEvent(event);
    state.isLoading = false;
    state.editEvent = null;
    store.addToastMessage({title: 'Action updated'});
};

const deleteEvent = async () => {
    state.isLoading = true;
    await destroyEvent(state.editEvent.id);
    pullEvent(state.editEvent);
    state.isLoading = false;
    state.editEvent = null;
    store.addToastMessage({title: 'Action deleted'});
};

const pushEvent = (event) => {
    const existingGameEventIndex = state.game.events.findIndex(playerEvent => playerEvent.id === event.id);
    if (existingGameEventIndex !== -1) {
        state.game.events[existingGameEventIndex] = event;
    } else {
        state.game.events.push(event);
    }

    const player = state.game.players.find(player => player.id === event.player_id);

    if (player) {
        const existingPlayerEventIndex = player.events.findIndex(playerEvent => playerEvent.id === event.id);
        if (existingPlayerEventIndex !== -1) {
            player.events[existingPlayerEventIndex] = event;
        } else {
            player.events.push(event);
        }
    }
};

const pullEvent = (event) => {
    const existingGameEventIndex = state.game.events.findIndex(gameEvent => gameEvent.id === event.id);

    if (existingGameEventIndex !== -1) {
        state.game.events.splice(existingGameEventIndex, 1);
    }

    const player = state.game.players.find(player => player.id === event.player_id);

    if (player) {
        const existingPlayerEventIndex = player.events.findIndex(playerEvent => playerEvent.id === event.id);
        if (existingPlayerEventIndex !== -1) {
            player.events.splice(existingPlayerEventIndex, 1);
        }
    }
};

const editEvent = (event) => {
    state.editEvent = event;
};

const startTimers = () => {
    state.timersEnabled = true;
};

const stopTimers = () => {
    state.timersEnabled = false;
};
getGame(props.id);
loadPositions();
loadGamePlayerTypes();
loadPlayerActionEventTypes();
</script>
