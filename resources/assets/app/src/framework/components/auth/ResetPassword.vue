<template>
    <Heading heading="Reset Password" />
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 sm:shadow sm:rounded-lg sm:px-10">
            <FlashMessage />
            <form role="form" @submit.prevent="submitForm" class="space-y-6">
                <InputField v-model="state.email" id="email" type="hidden" />
                <InputField v-model="state.token" id="token" type="hidden" />
                <InputField v-model="state.password" id="password" type="password" label="Password" />
                <InputField v-model="state.password_confirmation" id="password_confirmation" type="password" label="Confirm password" />
                <InputButton label="Change password" styles="w-full" />
                <div class="flex items-center justify-center">
                    <div class="text-sm">
                        <Link route="auth.forgot-password" text="Request new password reset link" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
<script setup>
import Heading from '@/framework/components/auth/Heading.vue';
import InputField from '@/framework/components/common/form/InputField.vue';
import {onMounted, reactive} from 'vue';
import {useRouter} from 'vue-router';
import InputButton from '@/framework/components/common/form/InputButton.vue';
import {resetPassword} from '@/framework/components/auth/auth.api.js';
import Link from '@/framework/components/auth/Link.vue';
import FlashMessage from '@/framework/components/common/alerts/FlashMessage.vue';
import {useStore} from '@/framework/store/index.js';

const router = useRouter();
const store = useStore();

const state = reactive({
    email: null,
    token: null,
    password: null,
    password_confirmation: null,
});

let isLoading = false;

const submitForm = async () => {
    isLoading = true;
    try {
        const {message} = await resetPassword(state);
        store.setFlashMessage({title: message});
        await router.push({name: 'auth.login'});
    } finally {
        state.password = null;
        state.password_confirmation = null;
        isLoading = false;
    }
};

onMounted(() => {
    state.email = router.currentRoute.value.query.email;
    state.token = router.currentRoute.value.params.token;
});
</script>
