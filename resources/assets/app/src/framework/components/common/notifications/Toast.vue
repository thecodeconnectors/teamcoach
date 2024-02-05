<template>
    <transition enter-active-class="transform ease-out duration-300 transition" enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" enter-to-class="translate-y-0 opacity-100 sm:translate-x-0" leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="show" class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <Icon prefix="far" name="check-circle" class="h-6 w-6 text-green-400" />
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-gray-900">
                            {{ toast.title }}
                        </p>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ toast.text }}
                        </p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="dismissToast" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <span class="sr-only">Close</span>
                            <Icon name="times" class="h-6 w-6" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>
<script>

import Icon from '@/framework/components/common/icon/Icon.vue';

export default {
    name: 'ToastMessage',
    components: {Icon},
    props: {
        toast: {
            type: Object,
            required: true,
        },
        index: {
            type: [String, Number],
            required: true,
        }
    },
    data() {
        return {
            show: true,
        };
    },
    methods: {
        dismissToast() {
            this.show = false;
            setTimeout(() => {
                this.$emit('dismissToast');
            }, 1000);
        },
    },
    mounted() {
        setTimeout(() => {
            this.dismissToast();
        }, 2000);
    }
};
</script>
