<template>
    <TransitionRoot as="template" :show="open">
        <Dialog as="div" class="fixed z-80 inset-0 overflow-y-auto" @close="open = false; isLoading = false;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                    <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                </TransitionChild>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center sm:mx-0 sm:h-10 sm:w-10">
                                <Icon name="exclamation-circle" class="h-8 w-8 text-red-300" />
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <DialogTitle as="h3" class="text-lg leading-6 font-medium text-gray-900">
                                    {{ title }}
                                </DialogTitle>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        {{ text }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-4 flex justify-between flex-row-reverse">
                            <InputButton @click="confirm" :is-loading="isLoading" type="button" :label="confirmButtonText" color="red" styles="ml-3" />
                            <InputButton @click="cancel" type="button" :label="cancelButtonText" color="white" text-color="gray-600" />
                        </div>
                    </div>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
<script setup>
import {Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot,} from '@headlessui/vue';
import Icon from '@/framework/components/common/icon/Icon.vue';
import InputButton from '@/framework/components/common/form/InputButton.vue';

const emit = defineEmits(['confirm', 'cancel']);
const props = defineProps({
    title: {
        type: String,
        default: 'Confirm delete',
    },
    text: {
        type: String,
        default: 'Are you sure you want to delete this item? This action cannot be undone.',
    },
    cancelButtonText: {
        type: String,
        default: 'Cancel',
    },
    confirmButtonText: {
        type: String,
        default: 'Delete',
    },
    open: {
        type: Boolean,
        default: false,
    },
});

let isLoading = false;

const confirm = () => {
    isLoading = true;
    emit('confirm');
};

const cancel = () => {
    isLoading = false;
    emit('cancel');
};
</script>
