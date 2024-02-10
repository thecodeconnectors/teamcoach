<template>
    <Heading heading="Reset Password" />
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 sm:shadow sm:rounded-lg sm:px-10">
            <form role="form" @submit.prevent="submitForm" class="space-y-6">
                <InputField id="email" v-model="form.email" type="hidden" />
                <InputField v-model="form.token" id="token" type="hidden" />
                <InputField v-model="form.password" id="password" type="password" label="Password" />
                <InputField v-model="form.password_confirmation" id="password_confirmation" type="password" label="Confirm password" />
                <InputButton label="Change password" styles="w-full" />
            </form>
        </div>
    </div>
</template>
<script setup>
import Heading from '@/framework/components/auth/Heading.vue';
import InputField from '@/framework/components/common/form/InputField.vue';
import {onMounted, ref} from 'vue';
import {useRouter} from 'vue-router';
import InputButton from '@/framework/components/common/form/InputButton.vue';

const router = useRouter();
const form = ref({
    email: null,
    token: null,
});
let isLoading = false;

const submitForm = async () => {
    isLoading = true;
    // Perform your form submission logic here
    isLoading = false;
};

onMounted(() => {
    form.email.value = router.currentRoute.value.query.email;
    form.token.value = router.currentRoute.value.params.token;
});
</script>
