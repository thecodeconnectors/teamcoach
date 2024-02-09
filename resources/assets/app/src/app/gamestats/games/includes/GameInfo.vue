<template>
    <div class="sm:overflow-hidden">
        Game info todo
    </div>
</template>
<script setup>

const emit = defineEmits(['click']);

const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
});

</script>
