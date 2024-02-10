<template>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">{{ title }}</h1>
    </div>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8 pt-6 lg:grid lg:grid-cols-12 lg:gap-8">
        <main class="col-span-12">
            <form role="form" @submit.prevent="saveGame">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <DropDownSelect
                            label="Team"
                            v-if="state.teams.length"
                            v-model="state.game.team_id"
                            :options="state.teams"
                        />
                        <InputField id="opponent_name" v-model="state.game.opponent_name" label="Opponent" />
                        <Checkbox v-model="state.game.is_away_game" label="Is away game" id="is_away_game" />
                        <InputField type="datetime-local" id="start_at" v-model="state.game.start_at" label="Start" />
                        <InputField type="number" id="parts" v-model="state.game.parts" label="Parts" />
                        <InputField type="number" id="part_duration" v-model="state.game.part_duration" label="Part duration" />
                        <InputField type="number" id="break_duration" v-model="state.game.break_duration" label="Break duration" />
                    </div>
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="sm:col-span-6">
                            <table class="w-full">
                                <tr v-for="player in state.game.players" :key="player.id" :value="player.id">
                                    <td>
                                        <span class="w-full flex items-left items-center">
                                            <img :src="player.profile_picture" :alt="player.name" width="32" class="mr-3 bg-blue-600 border-white border-2 rounded-full shadow" />
                                            <span>{{ player.name }}</span>
                                        </span>
                                    </td>
                                    <td>
                                        <DropDownSelect
                                            v-if="state.positions.length"
                                            v-model="player.position"
                                            :options="state.positions"
                                        />
                                    </td>
                                    <td>
                                        <DropDownSelect
                                            v-if="state.gamePlayerTypes.length"
                                            v-model="player.type"
                                            :options="state.gamePlayerTypes"
                                        />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 flex justify-between">
                        <InputButton v-if="isEditForm" type="button" label="Delete" color="white" text-color="gray-600" @click="state.showConfirmDelete = true" />
                        <span v-else></span>
                        <InputButton v-if="isEditForm" type="button" label="Start" @click="startGame" />
                        <InputButton :is-loading="state.isLoading" type="submit" label="Save" />
                    </div>
                </div>
            </form>
        </main>
    </div>
    <Confirm @confirm="deleteCurrentGame" @cancel="state.showConfirmDelete = false" :open="state.showConfirmDelete" />
</template>
<script setup>
import {deleteGame, getGame, getGamePlayerTypes, storeGame, updateGame} from './games.api.js';
import {useRouter} from 'vue-router';
import {useStore} from '@/framework/store';
import Confirm from '@/framework/components/common/modals/Confirm.vue';
import InputButton from '@/framework/components/common/form/InputButton.vue';
import InputField from '@/framework/components/common/form/InputField.vue';
import {computed, reactive} from 'vue';
import {getPositions} from '@/app/gamestats/positions/positions.api.js';
import DropDownSelect from '@/framework/components/common/form/DropDownSelect.vue';
import {getTeams} from '@/app/gamestats/teams/teams.api.js';
import Checkbox from '@/framework/components/common/form/Checkbox.vue';

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
    showConfirmDelete: false,
    teams: [],
    positions: [],
    gamePlayerTypes: [],
    game: {
        id: null,
        opponent_name: null,
        start_at: null,
        is_away_game: false,
        parts: 2,
        part_duration: 45,
        break_duration: 15,
        players: [],
    },
});

const title = computed(() => (isEditForm.value ? 'Edit Game' : 'New Game'));
const isEditForm = computed(() => router.currentRoute.value.name === 'games.edit');

const loadTeams = async () => {
    const {data: teams} = await getTeams();
    state.teams = teams;
};
const loadPositions = async () => {
    const {data: positions} = await getPositions();
    state.positions = positions;
};

const loadGamePlayerTypes = async () => {
    const {data: gamePlayerTypes} = await getGamePlayerTypes();
    state.gamePlayerTypes = gamePlayerTypes;
};

const getGameById = async (id) => {
    state.isLoading = true;
    const {data: game} = await getGame(id);
    state.game = game;
    state.isLoading = false;
};

const saveGame = async () => {
    state.isLoading = true;
    try {
        const response = isEditForm.value ? await updateGame(state.game.id, state.game) : await storeGame(state.game);

        if (response) {
            state.game = response.data;
            state.isLoading = false;

            store.addToastMessage({title: 'Game saved'});

            if (!isEditForm.value) {
                await router.push({name: 'games.edit', params: {id: state.game.id}});
            }
        }
    } catch (error) {
        state.isLoading = false;
    }
};

const startGame = async () => {
    await router.push(`/games/${state.game.id}/play`);
};

const deleteCurrentGame = async () => {
    await deleteGame(state.game.id);
    store.addToastMessage({title: 'Game deleted'});
    await router.push({name: 'games'});
};

loadTeams();
loadPositions();
loadGamePlayerTypes();

state.game.parts = store.settings.default_game_parts;
state.game.part_duration = store.settings.default_part_duration;
state.game.break_duration = store.settings.default_break_duration;

if (isEditForm.value) {
    getGameById(props.id);
}
</script>
