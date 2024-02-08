<template>
    <span class="ml-0.5 mr-1">
        <span v-for="n in goals" class="-ml-1 border border-transparent">
            <Icon name="soccer-ball" class="bg-white text-gray-500" />
        </span>
    </span>
</template>

<script setup>

import Icon from '@/framework/components/common/icon/Icon.vue';
import {computed} from 'vue';

const props = defineProps({
    events: {
        type: [Array, Object],
        default: [],
    },
});

const goals = computed(() => props.events.filter(event => event.type === 'goal'));

</script>
