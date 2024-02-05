<template>
    <button :type="type" @click="onClick" :class="`flex justify-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-${textColor} bg-${color}-600 hover:bg-${color}-700 focus:outline-none disabled:text-gray-500 disabled:bg-gray-100 ${styles}`">
        <Spinner v-if="isLoading" :color="loaderColor" />
        {{ label }}
    </button>
</template>
<script setup>
import {computed, ref, unref} from 'vue';
import Spinner from '@/framework/components/common/spinner/Spinner.vue';

const emit = defineEmits(['click']);

const props = defineProps({
    type: {
        type: String,
        default: 'submit',
    },
    label: {
        type: String,
        default: null,
    },
    color: {
        type: String,
        default: 'blue',
    },
    textColor: {
        type: String,
        default: 'white',
    },
    styles: {
        type: String,
        default: null,
    },
    isLoading: {
        type: Boolean,
        default: false,
    },
});

const loaderColor = computed(() => props.textColor);

const input = ref(props.type);

const onClick = () => {
    emit('click', unref(input));
};
</script>
