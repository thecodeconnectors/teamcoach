<template>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">{{ title }}</h1>
    </div>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8 pt-6 lg:grid lg:grid-cols-12 lg:gap-8">
        <main class="col-span-12">
            <form role="form" @submit.prevent="savePlayer">
                <div class="sm:shadow sm:rounded-md sm:overflow-hidden">
                    <div class="py-5 bg-white space-y-6 sm:p-6">

                        <InputField id="name" v-model="state.player.name" label="Name" />

                        <DropDownSelect
                            label="Preferred Position"
                            v-if="state.positions"
                            v-model="state.player.position"
                            :options="state.positions"
                        />

                        <DropDownSelect
                            label="Main Team"
                            v-if="state.teams"
                            v-model="state.player.team_id"
                            :options="state.teams"
                        />

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Avatar</label>
                            <div class="flex items-center">
                            <span v-for="avatar in state.avatars">
                                <img :src="avatar.id" :alt="avatar.name" class="mr-3 bg-blue-600 border-white border-2 rounded-full shadow" :width="state.player.avatar === avatar.name ? 64 : 32" @click="state.player.avatar = avatar.name" />
                            </span>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 flex justify-between">
                        <InputButton v-if="isEditForm && hasPermission('player.delete')" type="button" label="Delete" color="white" text-color="gray-600" @click="showConfirmDelete = true" />
                        <span v-else></span>
                        <InputButton v-if="(isEditForm && hasPermission('player.update')) || hasPermission('player.create')" :is-loading="isLoading" type="submit" label="Save" />
                    </div>
                </div>
            </form>
        </main>
    </div>
    <Confirm @confirm="deleteCurrentPlayer" @cancel="state.showConfirmDelete = false" :open="state.showConfirmDelete" />
</template>
<script setup>
import {deletePlayer, getPlayer, storePlayer, updatePlayer} from './players.api.js';
import {useRouter} from 'vue-router';
import {useStore} from '@/framework/store';
import Confirm from '@/framework/components/common/modals/Confirm.vue';
import InputButton from '@/framework/components/common/form/InputButton.vue';
import InputField from '@/framework/components/common/form/InputField.vue';
import {computed, reactive} from 'vue';
import {getPositions} from '@/app/gamestats/positions/positions.api.js';
import DropDownSelect from '@/framework/components/common/form/DropDownSelect.vue';
import {getAvatars} from '@/app/gamestats/avatars/avatars.api.js';
import {getTeams} from '@/app/gamestats/teams/teams.api.js';
import {useAuth} from '@/framework/composables/use-auth.js';

const {hasPermission} = useAuth();
const store = useStore();
const router = useRouter();

const props = defineProps({
    id: {
        type: [Number, String],
        default: null,
    },
});

const state = reactive({
    id: null,
    isLoading: false,
    showConfirmDelete: false,
    player: {
        id: null,
        name: null,
        avatar: null,
    },
    positions: [],
    teams: [],
});

const title = computed(() => (isEditForm.value ? 'Edit Player' : 'New Player'));
const isEditForm = computed(() => router.currentRoute.value.name === 'players.edit');

const loadPositions = async () => {
    const {data: positions} = await getPositions();
    state.positions = positions;
};

const loadTeams = async () => {
    const {data: teams} = await getTeams();
    state.teams = teams;
};

const loadAvatars = async () => {
    const {data: avatars} = await getAvatars();
    state.avatars = avatars;
};

const getPlayerById = async (id) => {
    state.isLoading = true;
    const {data: player} = await getPlayer(id);
    state.player = player;
    state.isLoading = false;
};

const savePlayer = async () => {
    state.isLoading = true;
    try {
        const response = isEditForm.value ? await updatePlayer(state.player.id, state.player) : await storePlayer(state.player);

        if (response) {
            state.player = response.data;
            state.isLoading = false;

            store.addToastMessage({title: 'Player saved'});

            if (!isEditForm.value) {
                await router.push({name: 'players.edit', params: {id: state.player.id}});
            }
        }
    } catch (error) {
        state.isLoading = false;
    }
};

const deleteCurrentPlayer = async () => {
    await deletePlayer(state.player.id);
    store.addToastMessage({title: 'Player deleted'});
    await router.push({name: 'players'});
};

loadPositions();
loadAvatars();
loadTeams();
if (isEditForm.value) {
    state.id = props.id;
    getPlayerById(state.id);
}
</script>
