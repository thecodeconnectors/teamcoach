<template>
       <span :class="`p-2 rounded-full ${size} ${customColor ? customColor : icon.color} flex items-center justify-center`">
           <Icon prefix="fa" :name="customIcon? customIcon: icon.name" class="w-full h-full text-white" />
       </span>
</template>

<script setup>
import Icon from '@/framework/components/common/icon/Icon.vue';
import {computed} from 'vue';

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
    size: {
        type: String,
        default: 'h-6 w-6',
    }
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

    if (props.event === 'corner') {
        return {
            name: 'flag',
            color: 'bg-cyan-600',
        };
    }

    if (props.event === 'free-kick') {
        return {
            name: 'feather-pointed',
            color: 'bg-pink-600',
        };
    }

    if (props.event === 'penalty') {
        return {
            name: 'circle-dot',
            color: 'bg-violet-600',
        };
    }
    if (props.event === 'foul') {
        return {
            name: 'triangle-exclamation',
            color: 'bg-orange-600',
        };
    }

    if (props.event === 'yellow-card') {
        return {
            name: 'file',
            color: 'bg-yellow-400',
        };
    }

    if (props.event === 'red-card') {
        return {
            name: 'file',
            color: 'bg-red-600',
        };
    }

    return {
        name: 'soccer-ball',
        color: 'bg-blue-600',
    };
});
</script>
