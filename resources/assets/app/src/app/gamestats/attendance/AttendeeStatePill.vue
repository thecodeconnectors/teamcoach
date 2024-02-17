<template>
    <StatusPill :text="attendee.state" :color="color" />
</template>
<script setup>

import StatusPill from '@/framework/components/common/pills/StatusPill.vue';
import {computed} from 'vue';

const props = defineProps({
    attendee: {
        type: Object,
        required: true,
    },
});

const color = computed(() => {
    const colors = {
        pending: 'gray',
        declined: 'red',
        accepted: 'green',
    };
    return colors[props.attendee.state] ?? 'green';
});
</script>
