<template>
    <router-link
        :to="linkUrl"
        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
    >
        <Icon :name="icon" class="-ml-0.5 mr-2 h-4 w-4" />
        {{ linkText }}
    </router-link>
</template>

<script setup>
import Icon from '@/framework/components/common/icon/Icon.vue';

const {linkUrl, linkText = null, icon = null} = defineProps({
    linkUrl: String,
    linkText: String,
    icon: String,
});
</script>
