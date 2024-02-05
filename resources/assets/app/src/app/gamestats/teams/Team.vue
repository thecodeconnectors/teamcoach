<template>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">{{ title }}</h1>
    </div>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8 pt-6 lg:grid lg:grid-cols-12 lg:gap-8">
        <main class="col-span-12">
            <form role="form" @submit.prevent="saveTeam">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="sm:col-span-6">
                            <InputField id="name" v-model="team.name" label="Name" />
                        </div>
                    </div>

                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 flex justify-between">
                        <InputButton v-if="isEditForm" type="button" label="Delete" color="white" text-color="gray-600" @click="showConfirmDelete = true" />
                        <span v-else></span>
                        <InputButton :is-loading="isLoading" type="submit" label="Save" />
                    </div>
                </div>
            </form>
        </main>
    </div>
    <Confirm @confirm="deleteCurrentTeam" @cancel="showConfirmDelete = false" :open="showConfirmDelete" />
</template>
<script setup>
import {deleteTeam, getTeam, storeTeam, updateTeam} from './teams.api.js';
import {useRouter} from 'vue-router';
import {useStore} from '@/framework/store';
import Confirm from '@/framework/components/common/modals/Confirm.vue';
import InputButton from '@/framework/components/common/form/InputButton.vue';
import InputField from '@/framework/components/common/form/InputField.vue';
import {computed, onMounted, ref} from 'vue';

const store = useStore();
const router = useRouter();

const props = defineProps({
    id: {
        type: [Number, String],
        default: null,
    },
});

const id = ref(null);
const isLoading = ref(false);
const showConfirmDelete = ref(false);
const team = ref({
    id: null,
    name: null,
});

const title = computed(() => (isEditForm.value ? 'Edit Team' : 'New Team'));
const isEditForm = computed(() => router.currentRoute.value.name === 'teams.edit');

const getTeamById = async (id) => {
    isLoading.value = true;
    const {data: teamData} = await getTeam(id);
    team.value = teamData;
    isLoading.value = false;
};

const saveTeam = async () => {
    isLoading.value = true;
    try {
        const response = isEditForm.value ? await updateTeam(id.value, team.value) : await storeTeam(team.value);

        if (response) {
            team.value = response.data;
            isLoading.value = false;

            store.addToastMessage({title: 'Team saved'});

            if (!isEditForm.value) {
                await router.push({name: 'teams.edit', params: {id: team.value.id}});
            }
        }
    } catch (error) {
        isLoading.value = false;
    }
};

const deleteCurrentTeam = async () => {
    await deleteTeam(team.value.id);
    store.addToastMessage({title: 'Team deleted'});
    await router.push({name: 'teams'});
};

onMounted(() => {
    if (isEditForm.value) {
        id.value = props.id;
        getTeamById(id.value);
    }
});
</script>
