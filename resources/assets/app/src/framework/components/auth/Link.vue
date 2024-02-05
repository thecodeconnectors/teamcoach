<template>
    <div class="text-sm">
        <router-link :to="{ name: route }" class="font-medium text-blue-600 hover:text-blue-500">
            {{ text }}
        </router-link>
    </div>
</template>

<script setup>

const props = defineProps({
    route: {
        type: String,
        required: true,
    },
    text: {
        type: String,
        required: true,
    },
});
</script>
