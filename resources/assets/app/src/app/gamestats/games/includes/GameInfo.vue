<template>
    <div class="sm:overflow-hidden">
        <div class="shadow sm:rounded-md sm:overflow-hidden">
            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                <Checkbox v-model="game.is_away_game" label="Is away game" id="is_away_game" />
                <InputField type="datetime-local" id="start_at" v-model="game.start_at" label="Start" />
                <InputField type="number" id="parts" v-model="game.parts" label="Parts" />
                <InputField type="number" id="part_duration" v-model="game.part_duration" label="Part duration" />
                <InputField type="number" id="break_duration" v-model="game.break_duration" label="Break duration" />
            </div>
        </div>
    </div>
</template>
<script setup>

import Checkbox from '@/framework/components/common/form/Checkbox.vue';
import InputField from '@/framework/components/common/form/InputField.vue';

const emit = defineEmits(['click']);

const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
});

</script>
