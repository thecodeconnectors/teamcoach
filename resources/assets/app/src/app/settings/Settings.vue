<template>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Settings</h1>
    </div>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8 pt-6 lg:grid lg:gap-8">
        <main>
            <div class="sm:shadow sm:rounded-md sm:overflow-hidden mb-6">
                <div class="py-5 bg-white space-y-6 sm:p-6">
                    <h2 class="text-lg leading-6 font-medium text-gray-900 mb-3">Game settings</h2>
                    <InputField type="number" min="1" max="10" step="1" @input="saveSetting('default_game_parts')" id="default_game_parts" v-model.lazy="store.settings.default_game_parts" label="Default game parts" />
                    <InputField type="number" min="1" step="1" @input="saveSetting('default_part_duration')" id="default_part_duration" v-model.lazy="store.settings.default_part_duration" label="Default part duration" />
                    <InputField type="number" min="1" mstep="1" @input="saveSetting('default_break_duration')" id="default_break_duration" v-model.lazy="store.settings.default_break_duration" label="Default break duration" />
                </div>
            </div>
        </main>
    </div>
</template>
<script setup>
import {debounce} from '@/framework/helpers';
import {useStore} from '@/framework/store';
import {storeSetting} from '@/app/settings/settings.api';
import InputField from '@/framework/components/common/form/InputField.vue';

const store = useStore();

const saveSetting = debounce(key => {
    const value = store.settings[key].trim();
    storeSetting(key, {value});
}, 500);
</script>
