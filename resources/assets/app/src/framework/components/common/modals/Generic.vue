<template>
    <TransitionRoot as="template" :show="open">
        <Dialog as="div" class="fixed z-80 inset-0 overflow-y-auto" @close="open = false; cancel()">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                    <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                </TransitionChild>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <div :class="widthClass" class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:p-6">
                        <div class="mt-3 text-center sm:text-left">
                            <DialogTitle as="h3" class="text-lg leading-6 font-medium text-gray-900">
                                {{ title }}
                            </DialogTitle>
                            <div class="mt-2">
                                <slot></slot>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-4 flex flex-row-reverse">
                            <InputButton @click="confirm" :is-loading="isLoading" type="button" :label="actionButtonText" color="blue" styles="ml-3" />
                            <InputButton @click="cancel" type="button" :label="cancelButtonText" color="gray" />
                        </div>
                    </div>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script>
import {Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue';
import InputButton from '@/framework/components/common/form/InputButton.vue';

export default {
    components: {
        InputButton,
        Dialog,
        DialogOverlay,
        DialogTitle,
        TransitionChild,
        TransitionRoot,
    },
    props: {
        title: {
            type: String,
            default: 'Modal',
        },
        cancelButtonText: {
            type: String,
            default: 'Close',
        },
        actionButtonText: {
            type: String,
            default: 'Save',
        },
        widthClass: {
            type: String,
            default: 'sm:max-w-lg sm:w-full',
        },
        open: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            isLoading: false,
        };
    },
    watch: {
        open(newValue) {
            if (newValue) {
                this.isLoading = false;
            }
        },
    },
    methods: {
        cancel() {
            this.isLoading = false;
            this.$emit('cancel');
        },
        confirm() {
            this.isLoading = true;
            this.$emit('confirm');
        },
    },
};
</script>
