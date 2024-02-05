<template>
    <div v-show="isLoading" class="z-10 w-full overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom text-center overflow-hidden transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <Spinner
                        v-show="isLoading"
                        color="text-blue-500"
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
