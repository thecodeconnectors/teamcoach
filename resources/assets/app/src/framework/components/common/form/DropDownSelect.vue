<template>
    <div>
        <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700 mb-1">
            {{ label }}
        </label>
        <select
            :id="id"
            :value="modelValue"
            class="block w-full pl-3 pr-10 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm rounded-md"
            :disabled="$attrs.disabled"
            @change="onChange"
        >
            <option v-for="option in options" :key="option.id" :value="option.id">
                {{ option.name }}
            </option>
        </select>
        <div v-if="errors && errors.length" class="mt-1 text-xs text-red-600">
            <template v-for="error in errors" :key="error">{{ error }}</template>
        </div>
    </div>
</template>

<script setup>
import {computed} from 'vue';
import {useStore} from '@/framework/store';

const props = defineProps({
    options: {
        type: Array,
        default: () => [],
    },
    modelValue: {
        type: [String, Number],
        default: null,
    },
    id: {
        type: [String, Number],
        default: () => Date.now(),
    },
    label: {
        type: String,
        default: null,
    },
    inline: {
        type: Boolean,
        default: false,
    },
    flatStyle: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update:modelValue', 'onChange']);

const store = useStore();

const errors = computed(function () {
    return store.errors ? store.errors[props.id] : [];
});

const onChange = (event) => {
    emit('update:modelValue', event.target.value);
    emit('onChange', event.target.value);
};
</script>
