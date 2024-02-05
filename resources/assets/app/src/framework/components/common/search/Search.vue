<template>
    <input
        v-model="inputValue"
        @input="debouncedSearch($event.target.value)"
        type="search"
        placeholder="Search"
        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block sm:text-sm border-gray-300 rounded-md"
    />
</template>

<script setup>
import {debounce} from '@/framework/helpers';
import {defineEmits, defineProps, ref, watch} from 'vue';

const props = defineProps({
    debounceTimeout: {
        type: Number,
        default: 200,
    },
    modelValue: {
        type: [String, Number],
        default: null,
    },
});

const emit = defineEmits(['onSearch', 'update:modelValue']);

const inputValue = ref(props.modelValue);

const debouncedSearch = debounce((value) => {
    emit('update:modelValue', value);
    emit('onSearch', value);
}, props.debounceTimeout);

watch(() => props.modelValue, (newValue) => {
    inputValue.value = newValue;
});

watch(inputValue, (newValue) => {
    debouncedSearch(newValue);
});

</script>
