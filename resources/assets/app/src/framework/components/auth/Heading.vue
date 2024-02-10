<template>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="mx-auto h-12 w-12 text-center">
            <div class="flex items-center justify-center">
                <img width="80" height="80" class="logo h-10 -mt-1 w-auto hidden md:block" :src="store.logo" />
            </div>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            {{ heading }}
        </h2>
    </div>
</template>

<script setup>
import {useStore} from '@/framework/store/index.js';

const store = useStore();
const props = defineProps({
    heading: null,
});

</script>
