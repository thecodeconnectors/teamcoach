<template>
       <span :class="`h-8 w-8 rounded-full ${customColor ? customColor : icon.color} flex items-center justify-center`">
           <Icon prefix="fa" :name="customIcon? customIcon: icon.name" class="h-5 w-5 text-white" />
       </span>
</template>

<script setup>
import Icon from '@/framework/components/common/icon/Icon.vue';
import {computed, defineProps} from 'vue';

const props = defineProps({
    event: {
        type: String,
        default: 'goal',
    },
    customIcon: {
        type: String,
        default: null,
    },
    customColor: {
        type: String,
        default: null,
    },
});

const icon = computed(function () {

    if (props.event === 'game-break') {
        return {
            name: 'pause',
            color: 'bg-gray-400',
        };
    }

    if (props.event === 'play-time') {
        return {
            name: 'clock',
            color: 'bg-blue-600',
        };
    }

    if (props.event === 'substituted') {
        return {
            name: 'rotate',
            color: 'bg-gray-400',
        };
    }

    if (props.event === 'assist') {
        return {
            name: 'handshake-angle',
            color: 'bg-green-600',
        };
    }

    if (props.event === 'defensive-action') {
        return {
            name: 'shield-halved',
            color: 'bg-purple-600',
        };
    }

    if (props.event === 'goal-stopped') {
        return {
            name: 'ban',
            color: 'bg-yellow-600',
        };
    }

    if (props.event === 'goal-lossed') {
        return {
            name: 'soccer-ball',
            color: 'bg-red-600',
        };
    }

    return {
        name: 'soccer-ball',
        color: 'bg-blue-600',
    };
});
</script>
