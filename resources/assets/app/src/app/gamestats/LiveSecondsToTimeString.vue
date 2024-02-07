<template>
    <span>{{ timeString }}</span>
</template>

<script setup>

import {secondsToTimeString} from '@/app/helpers.js';
import {computed, reactive, watchEffect} from 'vue';

const props = defineProps({
    seconds: {
        type: Number,
        default: 0,
    },
    intervalInSeconds: {
        type: Number,
        default: 1,
    },
    enabled: {
        type: Boolean,
        required: true,
    },
});

const state = reactive({
    timer: null,
    totalSeconds: 0,
});

const timeString = computed(() => secondsToTimeString(state.totalSeconds));

watchEffect(() => {
    state.totalSeconds = props.seconds;

    clearInterval(state.timer);
    if (props.enabled) {
        state.timer = setInterval(() => {
            state.totalSeconds += props.intervalInSeconds;
        }, props.intervalInSeconds * 1000);
    }

    return () => {
        clearInterval(state.timer);
    };
});

</script>
