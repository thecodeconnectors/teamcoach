<template>
    <Heading heading="Register" />
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 sm:shadow sm:rounded-lg sm:px-10">
            <form role="form" @submit.prevent="submitForm" class="space-y-6">
                <InputField id="name" v-model="form.name" label="Name" />
                <InputField id="email" v-model="form.email" type="email" label="Email address" validation-rules="required|email|minlength:3|maxlength:150" />
                <InputField id="password" v-model="form.password" type="password" label="Password" />
                <InputField id="password_confirmation" v-model="form.password_confirmation" type="password" label="Confirm password" />
                <div class="flex items-center justify-between">
                    <Checkbox v-model="form.accept_terms" label="Agree with T & C" id="accept_terms" />
                    <div class="text-sm">
                        <Link route="auth.login" text="Already a Member?" />
                    </div>
                </div>
                <InputButton :is-loading="isLoading" label="Register" styles="w-full" />
            </form>
        </div>
    </div>
</template>
<script setup>
import Heading from '@/framework/components/auth/Heading.vue';
import Link from '@/framework/components/auth/Link.vue';
import Checkbox from '@/framework/components/common/form/Checkbox.vue';
import InputField from '@/framework/components/common/form/InputField.vue';
import {ref} from 'vue';
import InputButton from '@/framework/components/common/form/InputButton.vue';
import {useStore} from '@/framework/store/index.js';
import {useRouter} from 'vue-router';
import {register} from '@/framework/components/auth/auth.api.js';

const router = useRouter();
const store = useStore();

const form = ref({});
let isLoading = false;

const submitForm = async () => {
    isLoading = true;
    try {
        const {message} = await register(form.value);
        store.setFlashMessage({text: message});
        await router.push({name: 'auth.login'});
    } catch (error) {
        isLoading = false;
    } finally {
        form.value = {};
        isLoading = false;
    }
};

</script>
