<template>
    <Heading heading="Verify email address" />
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 sm:shadow sm:rounded-lg sm:px-10">
            <FlashMessage />
            <form role="form" @submit.prevent="submitForm" class="space-y-6">
                <InputField id="email" v-model="form.email" type="email" label="Email address" validation-rules="required|email|minlength:3|maxlength:150" />
                <InputButton :is-loading="isLoading" label="Resend email verification link" styles="w-full" />
                <div class="flex items-center justify-center">
                    <div class="text-sm">
                        <Link route="auth.login" text="Login" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>

import Heading from '@/framework/components/auth/Heading.vue';
import Link from '@/framework/components/auth/Link.vue';
import {sendPasswordResetLink} from '@/framework/components/auth/auth.api';
import FlashMessage from '@/framework/components/common/alerts/FlashMessage.vue';
import InputButton from '@/framework/components/common/form/InputButton.vue';
import {ref} from 'vue';
import InputField from '@/framework/components/common/form/InputField.vue';
import {useStore} from '@/framework/store/index.js';

const store = useStore();

const form = ref({});
let isLoading = false;

const submitForm = async () => {
    isLoading = true;
    try {
        const {message} = await sendPasswordResetLink(form.value);
        store.setFlashMessage({text: message});
    } catch (error) {
        isLoading = false;
    } finally {
        form.value = {};
        isLoading = false;
    }
};
</script>
