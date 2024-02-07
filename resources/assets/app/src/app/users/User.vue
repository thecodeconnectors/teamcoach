<template>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">{{ title }}</h1>
    </div>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8 pt-6 lg:grid lg:grid-cols-12 lg:gap-8">
        <main class="col-span-12">
            <form role="form" @submit.prevent="saveUser">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="sm:col-span-6">
                            <InputField id="name" v-model="user.name" label="Name" />
                        </div>

                        <div class="sm:col-span-6">
                            <InputField id="email" v-model="user.email" type="email" label="Email" />
                        </div>

                        <div v-if="!isEditForm" class="sm:col-span-6">
                            <InputField id="password" v-model="user.password" type="password" label="Password" />
                        </div>

                        <div v-if="!isEditForm" class="sm:col-span-6">
                            <InputField id="password_confirmation" v-model="user.password_confirmation" type="password" label="Confirm password" />
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
    <Confirm @confirm="deleteUser" @cancel="showConfirmDelete = false" :open="showConfirmDelete" />
</template>

<script setup>
import {computed, ref} from 'vue';
import {useRouter} from 'vue-router';
import {useStore} from '@/framework/store';
import {deleteUser, getUser, storeUser, updateUser} from './users.api';
import {findImageFromCollection} from '@/framework/helpers.js';
import Confirm from '@/framework/components/common/modals/Confirm.vue';
import InputButton from '@/framework/components/common/form/InputButton.vue';
import InputField from '@/framework/components/common/form/InputField.vue';

const props = defineProps({
    id: {
        type: [Number, String],
        required: false,
    },
});

const user = ref({
    name: null,
    email: null,
    facebook: null,
    instagram: null,
    twitter: null,
    tiktok: null,
    snapchat: null,
    linkedin: null,
    password: null,
    show_on_frontend: null,
    include_in_reports: null,
    password_confirmation: null,
    profile_picture: {},
});
const isLoading = ref(false);
const showConfirmDelete = ref(false);
const router = useRouter();
const store = useStore();

const isEditForm = computed(() => router.currentRoute.value.name === 'users.edit');
const title = computed(() => isEditForm.value ? 'Edit User' : 'New User');
const profilePicture = computed(() => findImageFromCollection(user.value.media, 'profile_picture'));
const hasProfilePicture = computed(() => !!profilePicture.value?.id);

const fetchUser = async (id) => {
    isLoading.value = true;
    const {data: userData} = await getUser(id);
    user.value = userData;
    isLoading.value = false;
};

const saveUser = async () => {
    isLoading.value = true;
    try {
        const response = await (isEditForm.value ? updateUser(props.id, user.value) : storeUser(user.value));
        user.value = response.data;
        store.addToastMessage({title: 'User saved'});
        if (!isEditForm.value) {
            router.push({name: 'users.edit', params: {id: user.value.id}});
        }
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
    }
};

const performDeleteUser = async () => {
    await deleteUser(user.value.id);
    store.addToastMessage({title: 'User deleted'});
    router.push({name: 'users'});
};

const onUploadedFile = (media) => {
    user.value.profile_picture = media;
};

if (isEditForm.value) {
    fetchUser(props.id);
}
</script>
