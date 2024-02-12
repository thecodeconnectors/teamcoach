<template>
    <div>
        <label :for="id" class="block text-sm font-medium text-gray-700">
            {{ label }}
        </label>
        <div class="mt-1">
            <input
                :id="id"
                ref="input"
                :value="modelValue"
                v-bind="$attrs"
                :type="type"
                :autocomplete="$attrs.autocomplete || (type === 'password' ? 'current-password' : id)" formnovalidate
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                @blur="onBlur"
                @input="onInput"
                @change="onChange"
            />

            <div v-if="errors?.length" class="mt-1 text-xs text-red-600">
                <template v-for="error in errors">{{ error }}</template>
            </div>
        </div>
    </div>
</template>

<script setup>
import {computed} from 'vue';
import {useStore} from '@/framework/store';

const store = useStore();

const props = defineProps({
    type: {
        type: String,
        default: 'text',
    },
    modelValue: {
        type: [String, Number],
        default: null,
    },
    id: {
        type: String,
        default: () => Date.now().toString(),
    },
    label: {
        type: String,
        default: null,
    },
    validationRules: {
        type: String,
        default: null,
    },
});

const emit = defineEmits([
    'update:modelValue',
    'change',
    'input',
    'inputBlur'
]);

const errors = computed(function () {
    return store.errors ? store.errors[props.id] : [];
});

const onBlur = event => {
    emit('inputBlur', event.target.value);
};

const onInput = event => {
    emit('update:modelValue', event.target.value);
    emit('input', event.target.value);
};

const onChange = event => {
    emit('change', event.target.value);
};
</script>

<style scoped>
.required::after {
    content: '﹡';
}
</style>
