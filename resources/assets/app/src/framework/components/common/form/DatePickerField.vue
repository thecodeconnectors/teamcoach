<template>
    <div class="mb-3">
        <label :for="id" class="block text-sm font-medium text-gray-700">
            {{ label }}
        </label>
        <div class="mt-1">
            <DatePicker
                ref="input"
                :value="modelValue"
                :id="id"
                :model-config="modelConfig"
                color="blue"
                mode="dateTime"
                is24hr
            >
                <template v-slot="{ inputValue, inputEvents }">
                    <input
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        :value="inputValue"
                        v-on="inputEvents"
                    />
                </template>
            </DatePicker>
        </div>
    </div>
</template>

<script>
import {DatePicker} from 'v-calendar';
import 'v-calendar/dist/style.css';

export default {
    name: 'DatePickerField',
    components: {
        DatePicker
    },
    props: {
        label: {
            type: String,
            default: 'Content',
        },
        id: {
            type: String,
            default() {
                return Date.now().toString();
            },
        },
        modelValue: {
            type: String,
            default: null,
        },
    },
    data() {
        return {
            modelConfig: {
                type: 'string',
                mask: 'YYYY-MM-DD HH:mm',
            },
        };
    },
    watch: {
        modelValue(newValue) {
            this.$emit('input', newValue);
        },
    },
    emits: [
        'input',
    ],
};
</script>
