<template>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Settings</h1>
    </div>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8 pt-6 lg:grid lg:grid-cols-12 lg:gap-8">
        <main class="lg:col-span-9 xl:col-span-8">
            <div class="shadow sm:rounded-md sm:overflow-hidden mb-6">
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                    <div class="sm:col-span-6">
                        <h2 class="text-lg leading-6 font-medium text-gray-900 mb-3">General</h2>
                        <!--                        <InputField@keyup="updateSetting" id="general.title" v-model.lazy="settings['general.title']" type="text" label="Title" />-->
                        <!--                        <InputField@keyup="updateSetting" id="general.description" v-model.lazy="settings['general.description']" type="text" label="Description" />-->
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
<script setup>
import {debounce} from '@/framework/helpers';
import {computed} from 'vue';
import {useStore} from '@/framework/store';
import {updateSettings} from '@/app/settings/settings.api';

const store = useStore();

const settings = computed(() => {

});

const updateSetting = debounce((event) => {
    const key = event.target.id;
    const value = event.target.value.trim();
    updateSettings(key, {value});
}, 200);
</script>
