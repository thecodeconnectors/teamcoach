<template>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">{{ title }}</h1>
    </div>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8 pt-6 lg:grid lg:grid-cols-12 lg:gap-8">
        <main class="col-span-12">
            <form role="form" @submit.prevent="savePlayer">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="sm:col-span-6">
                            <InputField id="name" v-model="state.player.name" label="Name" />
                        </div>
                    </div>
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="sm:col-span-6">
                            <DropDownSelect
                                label="Position"
                                v-if="state.positions"
                                v-model="state.player.position"
                                :options="state.positions"
                            />
                        </div>
                    </div>

                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 flex justify-between">
                        <InputButton v-if="isEditForm" type="button" label="Delete" color="white" text-color="gray-600" @click="state.showConfirmDelete = true" />
                        <span v-else></span>
                        <InputButton :is-loading="state.isLoading" type="submit" label="Save" />
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
import {computed, onMounted, reactive} from 'vue';
import {getPositions} from '@/app/gamestats/positions/positions.api.js';
import DropDownSelect from '@/framework/components/common/form/DropDownSelect.vue';

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
    },
    positions: [],
});

const title = computed(() => (isEditForm.value ? 'Edit Player' : 'New Player'));
const isEditForm = computed(() => router.currentRoute.value.name === 'players.edit');

const loadPositions = async () => {
    const {data: positions} = await getPositions();
    state.positions = positions;
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

onMounted(() => {
    loadPositions();
    if (isEditForm.value) {
        state.id = props.id;
        getPlayerById(state.id);
    }
});
</script>
