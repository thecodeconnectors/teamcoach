<template>
    <TransitionRoot as="template" :show="open">
        <Dialog as="div" class="z-50 fixed inset-0 overflow-hidden" @close="close()">
            <div class="absolute inset-0 overflow-hidden">
                <DialogOverlay class="absolute inset-0" />

                <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
                    <TransitionChild as="template" enter="transform transition ease-in-out duration-200 sm:duration-200" enter-from="translate-x-full" enter-to="translate-x-0" leave="transform transition ease-in-out duration-200 sm:duration-200" leave-from="translate-x-0" leave-to="translate-x-full">
                        <div :class="widthClass">
                            <div class="h-full flex flex-col py-6 bg-white shadow-xl overflow-y-scroll">
                                <div class="px-4 sm:px-6">
                                    <div class="flex items-start justify-between">
                                        <DialogTitle class="text-lg font-medium text-gray-900">
                                            {{ title }}
                                        </DialogTitle>
                                        <div class="ml-3 h-7 flex items-center">
                                            <button @click="close()" type="button" class="rounded-md text-gray-400 hover:text-gray-500">
                                                <span class="sr-only">Close panel</span>
                                                <Icon name="times" class="h-6 w-6" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white mt-6 relative flex-1 px-4 sm:px-6">
                                    <slot></slot>
                                </div>
                            </div>
                        </div>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script>
import {Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue';
import Icon from '@/framework/components/common/icon/Icon.vue';

export default {
    name: 'SlideOver',
    components: {
        Icon,
        Dialog,
        DialogOverlay,
        DialogTitle,
        TransitionChild,
        TransitionRoot,
    },
    props: {
        open: {
            type: Boolean,
            default: false,
        },
        title: {
            type: String,
            default: '',
        },
        widthClass: {
            type: String,
            default: 'w-screen max-w-md'
        },
    },
    methods: {
        close() {
            this.$emit('close');
        }
    }
};
</script>
