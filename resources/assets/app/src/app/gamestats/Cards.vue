<template>
        <span v-for="card in cards" class="mt-0.5">
            <Icon v-if="card.type === 'red-card'" name="file" class="bg-white text-red-600 border border-white" />
            <Icon v-else name="file" class="bg-white text-yellow-400 border-white border" />
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

const cards = computed(() => props.events.filter(event => ['yellow-card', 'red-card'].includes(event.type)));
</script>
