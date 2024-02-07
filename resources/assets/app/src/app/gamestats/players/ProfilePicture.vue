<template>
    <img :src="src" :alt="alt" :width="width" class="border-4 border-white rounded-full" />
</template>

<script setup>

const props = defineProps({
    src: {
        type: String,
        required: true,
    },
    alt: {
        type: String,
        required: true,
    },
    width: {
        type: [Number, String],
        default: 32,
    },
});

</script>
