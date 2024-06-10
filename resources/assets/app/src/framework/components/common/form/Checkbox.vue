<template>
    <div class="flex items-center">
        <input :checked="modelValue" @change="onChange($event)" :id="id" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
        <label :for="id" class="ml-2 block text-sm dark:text-gray-50 text-gray-900 cursor-pointer">
            {{ label }}
        </label>
    </div>
</template>

<script setup>

const props = defineProps({
    modelValue: {
        type: [String, Number, Boolean],
        default: false,
    },
    id: {
        type: String,
        default: () => Date.now().toString(),
    },
    label: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(['update:modelValue']);

const onChange = (event) => {
    emit('update:modelValue', event.target.checked);
};
</script>
