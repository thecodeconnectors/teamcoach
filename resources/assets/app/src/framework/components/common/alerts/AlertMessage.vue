<template>
    <div :class="`rounded-md bg-${color}-50 p-2`">
        <div class="flex">
            <div class="flex-shrink-0 inline-flex items-center">
                <Icon :name="icon" :class="`h-5 w-5 text-${color}-400`" aria-hidden="true" />
            </div>
            <div class="ml-3">
                <p :class="`text-sm font-medium text-${color}-800`" v-html="message"></p>
            </div>
            <div class="ml-auto pl-3">
                <div v-if="dissmissable" class="-mx-1.5 -my-1.5">
                    <button type="button" :class="`inline-flex bg-${color}-50 rounded-md p-1.5 text-${color}-500 hover:bg-${color}-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-${color}-50 focus:ring-${color}-600`">
                        <span class="sr-only">Dismiss</span>
                        <Icon name="times" class="h-5 w-5" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import Icon from '@/framework/components/common/icon/Icon.vue';

const props = defineProps({
    message: {
        type: String,
        required: true,
    },
    color: {
        type: String,
        required: false,
        default: 'green',
    },
    icon: {
        type: String,
        required: false,
        default: 'check-circle',
    },
    dissmissable: {
        type: Boolean,
        required: false,
        default: false,
    },
});
</script>
