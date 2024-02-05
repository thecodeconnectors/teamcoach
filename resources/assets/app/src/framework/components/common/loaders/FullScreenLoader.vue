<template>
    <div v-show="isLoading" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!--
              Background overlay, show/hide based on modal state.

              Entering: "ease-out duration-300"
                From: "opacity-0"
                To: "opacity-100"
              Leaving: "ease-in duration-200"
                From: "opacity-100"
                To: "opacity-0"
            -->
            <div class="fixed inset-0 bg-gray-200 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom text-center overflow-hidden transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <Spinner
                        v-show="isLoading"
                        class="h-8 w-8 inline-block"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Spinner from '@/framework/components/common/spinner/Spinner.vue';

const props = defineProps({
    isLoading: {
        type: Boolean,
        required: true,
    },
});
</script>
