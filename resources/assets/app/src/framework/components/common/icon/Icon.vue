<template>
    <FontAwesomeIcon :icon="[prefix, name]" v-bind="$attrs" aria-hidden="true" />
</template>
<script setup>
import {defineProps} from 'vue';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';

const props = defineProps({
    prefix: {
        type: String,
        default: 'fas',
    },
    name: {
        type: String,
        required: true,
    },
});
</script>
