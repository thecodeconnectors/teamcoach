<template>
    <span :class="`rounded-md whitespace-nowrap mt-0.5 px-1.5 py-0.5 text-xs font-medium capitalize ring-1 ring-inset ${css}`">{{ text }}</span>
</template>
<script setup>

import {computed} from 'vue';

const props = defineProps({
    text: {
        type: String,
        required: true,
    },
    color: {
        type: String,
        required: false,
        default: 'green',
    },
});

const css = computed(() => {
    const classes = {
        gray: 'text-gray-700 bg-gray-50 ring-gray-600/20',
        red: 'text-red-700 bg-red-50 ring-red-600/20',
        green: 'text-green-700 bg-green-50 ring-green-600/20',
    };
    return classes[props.color] ?? 'green';
});

</script>
