<template>
    <Heading heading="Forgot Password" />
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <FlashMessage />
            <form role="form" @submit.prevent="submitForm" class="space-y-6">
                <InputField id="email" v-model="form.email" type="email" label="Email address" validation-rules="required|email|minlength:3|maxlength:150" />
                <InputButton :is-loading="isLoading" label="Send Password Reset Link" styles="w-full" />
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

const form = ref({});
let isLoading = false;

const submitForm = async () => {
    isLoading = true;
    try {
        await sendPasswordResetLink(form.value);
    } catch (error) {
        isLoading = false;
    } finally {
        form.value = {};
        isLoading = false;
    }
};
</script>
