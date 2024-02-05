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

<script>
import {deleteUser, getUser, storeUser, updateUser} from './users.api';
import {useRouter} from 'vue-router';
import {useStore} from '@/framework/store';
import {findImageFromCollection} from '@/framework/helpers.js';
import Confirm from '@/framework/components/common/modals/Confirm.vue';
import SidebarPanel from '@/framework/components/common/panels/SidebarPanel.vue';
import MediaUpload from '@/framework/components/common/form/MediaUpload.vue';
import InputButton from '@/framework/components/common/form/InputButton.vue';
import Checkbox from '@/framework/components/common/form/Checkbox.vue';
import SlugField from '@/framework/components/common/form/SlugField.vue';
import InputField from '@/framework/components/common/form/InputField.vue';

export default {
    name: 'User',
    components: {InputField, SlugField, Checkbox, InputButton, MediaUpload, SidebarPanel, Confirm},
    props: {
        id: {
            type: [Number, String],
            required: false,
        },
    },
    data() {
        return {
            user: {
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
            },
            isLoading: false,
            showConfirmDelete: false,
        };
    },
    computed: {
        title() {
            return this.isEditForm ? 'Edit User' : 'New User';
        },

        isEditForm() {
            const router = useRouter();
            return router.currentRoute.value.name === 'users.edit';
        },

        profilePicture() {
            return findImageFromCollection(this.user.media, 'profile_picture');
        },

        hasProfilePicture() {
            return this.profilePicture?.id;
        },
    },
    methods: {
        async getUser(id) {
            const {data: user} = await getUser(id);
            this.user = user;
            this.isLoading = false;
        },
        async saveUser() {
            this.isLoading = true;
            await (this.isEditForm ? updateUser(this.id, this.user) : storeUser(this.user)).catch(() => {
                this.isLoading = false;
            }).then((response) => {
                if (response) {
                    this.user = response.data;
                    this.isLoading = false;

                    const store = useStore();
                    const router = useRouter();
                    store.addToastMessage({title: 'User saved'});

                    if (!this.isEditForm) {
                        router.push({name: 'users.edit', params: {id: this.user.id}});
                    }
                }
            });
        },
        async deleteUser() {
            await deleteUser(this.user.id).then(() => {
                const store = useStore();
                const router = useRouter();
                store.addToastMessage({title: 'User deleted'});
                router.push({name: 'users'});
            });
        },
        onUploadedFile(media) {
            this.user.profile_picture = media;
        },
    },
    mounted() {
        if (this.isEditForm) {
            this.getUser(this.id);
        }
        this.isLoading = false;
    },
};
</script>
