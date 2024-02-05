<template>
    <div class="mb-3">
        <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700">
            {{ label }}
        </label>
        <div class="mt-1 h-full">
            <textarea
                :id="id"
                ref="input"
                :value="modelValue"
                v-bind="$attrs"
                :autocomplete="$attrs.autocomplete || (type === 'password' ? 'current-password' : id)"
                formnovalidate
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                @blur="onBlur"
                @input="onInput"
                @change="onChange"
            />
            <div v-if="errors && errors.length" class="mt-1 text-xs text-red-600">
                <template v-for="error in errors">{{ error }}</template>
            </div>
        </div>
    </div>
</template>

<script>
import {useStore} from '@/framework/store';
import {computed} from 'vue';

export default {
    name: 'InputTextarea',
    props: {
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
            default() {
                return Date.now().toString();
            },
        },
        label: {
            type: String,
            default: null,
        },
        validationRules: {
            type: String,
            default: null,
        },
    },
    emits: [
        'update:modelValue',
        'change',
        'inputBlur',
    ],
    methods: {
        focusInput() {
            this.$refs.input.focus();
        }
    },
    setup(props, {emit}) {
        const store = useStore();
        const errors = computed(function () {
            return store.errors ? store.errors[props.id] : [];
        });

        const onBlur = event => {
            emit('inputBlur', event.target.value);
        };

        const onInput = event => {
            emit('update:modelValue', event.target.value);
        };

        const onChange = event => {
            emit('change', event.target.value);
        };

        return {
            errors,
            onInput,
            onChange,
            onBlur,
        };
    },
};
</script>

<style scoped>
.required::after {
    content: '﹡';
}
</style>
