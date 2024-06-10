<template>
    <Heading heading="Login" />
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white dark:bg-gray-800 py-8 px-4 sm:shadow sm:rounded-lg sm:px-10">
            <FlashMessage />
            <form role="form" @submit.prevent="submitForm" class="space-y-6">
                <InputField v-model="form.email" id="email" type="email" label="Email address" validation-rules="required|email|minlength:3|maxlength:150" />
                <InputField v-model="form.password" id="password" type="password" label="Password" />
                <div class="flex items-center justify-between">
                    <Checkbox v-model="form.remember" label="Remember me" id="remember" />
                    <div class="text-sm">
                        <Link route="auth.forgot-password" text="Forgot your password?" />
                    </div>
                </div>
                <InputButton :is-loading="isLoading" label="Sign in" styles="w-full" />
                <div class="flex items-center justify-center">
                    <div class="text-sm">
                        <Link route="auth.register" text="Create an account" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
<script setup>
import {reactive} from 'vue';
import {getUser} from '@/framework/components/auth/auth.api';
import {useRouter} from 'vue-router';
import {useStore} from '@/framework/store';
import FlashMessage from '@/framework/components/common/alerts/FlashMessage.vue';
import Heading from '@/framework/components/auth/Heading.vue';
import Link from '@/framework/components/auth/Link.vue';
import Checkbox from '@/framework/components/common/form/Checkbox.vue';
import InputField from '@/framework/components/common/form/InputField.vue';
import InputButton from '@/framework/components/common/form/InputButton.vue';
import {login} from '@/framework/components/auth/auth.api.js';

const router = useRouter();
const store = useStore();

const form = reactive({
    email: null,
    password: null,
    remember: null,
    isLoading: false
});

let isLoading = false;

const submitForm = async () => {
    isLoading = true;
    await login(form);
    const response = await getUser();
    await store.setUser(response.data);
    await router.push({name: 'home'});
    isLoading = false;
};

</script>
