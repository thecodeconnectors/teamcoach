<template>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">{{ title }}</h1>
    </div>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8 pt-6 lg:grid lg:grid-cols-12 lg:gap-8">
        <main class="col-span-12">
            <form role="form" @submit.prevent="saveTraining">
                <div class="sm:shadow sm:rounded-md sm:overflow-hidden">
                    <div class="py-5 bg-white space-y-6 sm:p-6">
                        <InputField type="datetime-local" id="start_at" v-model="state.training.start_at" label="Start" />
                        <DropDownSelect
                            label="Team"
                            id="team_id"
                            v-if="state.teams"
                            v-model="state.training.team_id"
                            :options="state.teams"
                        />

                        <div v-if="state.training.attendees?.length" class="space-y-6">
                            <div v-for="attendee in state.training.attendees" :key="attendee.id">
                                <button
                                    type="button"
                                    class="border rounded p-2 w-full flex items-center justify-between"
                                >
                                    <span class="flex items-center justify-between">
                                         <ProfilePicture :src="attendee.profile_picture" :alt="attendee.name" width="28" />
                                         <span class="ml-2">{{ attendee.name }}</span>
                                    </span>
                                    <AttendeeState
                                        :endpoint="`training/${state.training.id}/players/${attendee.attendee_id}`"
                                        :attendable="state.training"
                                        :attendee="attendee"
                                        @onChange="setAttendeeState"
                                    />
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 flex justify-between">
                        <InputButton v-if="isEditForm && hasPermission('training.delete')" type="button" label="Delete" color="white" text-color="gray-600" @click="state.showConfirmDelete = true" />
                        <span v-else></span>
                        <InputButton v-if="(isEditForm && hasPermission('training.update')) || hasPermission('training.create')" type="button" label="Add attendees" @click="state.showAddAttendees = true" />
                        <InputButton v-if="(isEditForm && hasPermission('training.update')) || hasPermission('training.create')" :is-loading="state.isLoading" type="submit" label="Save" />
                    </div>
                </div>
            </form>
        </main>
    </div>
    <Confirm @confirm="deleteCurrentTraining" @cancel="state.showConfirmDelete = false" :open="state.showConfirmDelete" />
    <SlideOver title="Add attendees"
               :open="state.showAddAttendees"
               @close="state.showAddAttendees = false"
               width-class="w-screen max-w-2xl">
        <div v-if="state.training.players?.length" class="space-y-6">
            <div v-for="player in state.training.players" :key="player.id">
                <button
                    type="button"
                    @click="toggleAttendee(player)"
                    :class="['border rounded p-2 w-full flex items-center justify-between', attendeeIsAdded(player) ? 'bg-blue-500 text-white shadow' : '']"
                >
                    <span class="flex items-center justify-between">
                         <ProfilePicture :src="player.profile_picture" :alt="player.name" width="28" />
                        <span class="ml-2">{{ player.name }}</span>
                    </span>
                    <Icon v-if="attendeeIsAdded(player)" prefix="far" name="check-circle" class="h-4 w-4 text-white" />
                </button>
            </div>
            <InputButton :is-loading="state.isLoading" type="button" label="Save" class="w-full" @click="saveAttendeeList" />
        </div>
    </SlideOver>
</template>
<script setup>
import {deleteTraining, getTraining, storeTraining, storeTrainingAttendance, updateTraining} from './training.api.js';
import {useRouter} from 'vue-router';
import {useStore} from '@/framework/store';
import Confirm from '@/framework/components/common/modals/Confirm.vue';
import InputButton from '@/framework/components/common/form/InputButton.vue';
import InputField from '@/framework/components/common/form/InputField.vue';
import {computed, reactive} from 'vue';
import {useAuth} from '@/framework/composables/use-auth.js';
import DropDownSelect from '@/framework/components/common/form/DropDownSelect.vue';
import {getTeams} from '@/app/gamestats/teams/teams.api.js';
import Icon from '@/framework/components/common/icon/Icon.vue';
import ProfilePicture from '@/app/gamestats/players/ProfilePicture.vue';
import {listContainsObject, toggleListObject} from '@/framework/helpers.js';
import SlideOver from '@/framework/components/common/modals/SlideOver.vue';
import AttendeeState from '@/app/attendance/AttendeeState.vue';

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
    showAddAttendees: false,
    training: {
        id: null,
        start_at: null,
        team_id: null,
        team_name: null,
        players: null,
        attendees: null,
    },
    teams: [],
    newAttendees: [],
});

const isEditForm = computed(() => router.currentRoute.value.name === 'training.edit');
const title = computed(() => (isEditForm.value ? 'Edit Training' : 'New Training'));

const loadTeams = async () => {
    const {data: teams} = await getTeams();
    state.teams = teams;
};

const getTrainingById = async (id) => {
    state.isLoading = true;
    const {data: trainingData} = await getTraining(id);
    state.training = trainingData;
    state.isLoading = false;
};

const saveTraining = async () => {
    state.isLoading = true;
    try {
        const response = state.isEditForm ? await updateTraining(state.id, state.training) : await storeTraining(state.training);

        if (response) {
            state.training = response.data;
            state.isLoading = false;

            store.addToastMessage({title: 'Training saved'});

            if (!isEditForm.value) {
                await router.push({name: 'training.edit', params: {id: state.training.id}});
            }
        }
    } catch (error) {
        state.isLoading = false;
    }
};

const deleteCurrentTraining = async () => {
    await deleteTraining(state.training.id);
    store.addToastMessage({title: 'Training deleted'});
    await router.push({name: 'training'});
};

const saveAttendeeList = async () => {
    state.isLoading = true;
    try {
        const response = await storeTrainingAttendance(state.training.id, state.newAttendees.map(attendee => attendee.id));

        if (response) {
            state.training = response.data;
            state.isLoading = false;

            store.addToastMessage({title: 'Attendees added'});
        }

    } catch (error) {
        state.isLoading = false;
    }
};

const toggleAttendee = player => {
    state.newAttendees = toggleListObject(state.newAttendees, player);
};

const attendeeIsAdded = player => listContainsObject(state.newAttendees, player);

const setAttendeeState = (player, attendeeState) => player.state = attendeeState;

loadTeams();
if (isEditForm.value) {
    state.id = props.id;
    getTrainingById(state.id);
}
</script>
