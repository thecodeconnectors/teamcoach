<template>
    <span v-for="n in goalRange" class="-ml-1">
        <Icon name="soccer-ball" class="bg-white text-gray-500" />
    </span>
</template>

<script setup>

import {computed} from 'vue';
import Icon from '@/framework/components/common/icon/Icon.vue';

const props = defineProps({
    goals: {
        type: Number,
        default: 1,
    },
});
const goalRange = computed(() => Array.from({length: props.goals}, (_, i) => i + 1));

</script>
