<template>
    <Disclosure as="div" :class="classes" :defaultOpen="open" v-slot="{ open }">
        <h3 class="flow-root px-4 py-5 space-y-6 sm:p-6 bg-white">
            <DisclosureButton class="w-full flex items-center justify-between text-gray-400 hover:text-gray-500">
                <span class="text-sm font-medium text-gray-700">
                    {{ title }}
                </span>
                <span class="flex items-center">
                    <div v-if="checked !== null">
                        <Icon prefix="far" name="check-circle" class="text-green-500" v-if="checked" />
                        <Icon name="exclamation-circle" class="text-red-500" v-else-if="warn" />
                    </div>
                    <span class="w-6 text-center">
                        <Icon name="angle-down" v-if="!open" />
                        <Icon name="angle-left" v-else />
                    </span>
                </span>
            </DisclosureButton>
        </h3>
        <DisclosurePanel class="border-t border-gray-200 space-y-6 text-sm font-normal text-gray-700 px-4 py-5 space-y-6 sm:p-6">
            <slot></slot>
        </DisclosurePanel>
    </Disclosure>
</template>
<script>
import {Disclosure, DisclosureButton, DisclosurePanel,} from '@headlessui/vue';
import Icon from '@/framework/components/common/icon/Icon.vue';

export default {
    name: 'SidebarPanel',
    components: {
        Icon,
        Disclosure,
        DisclosureButton,
        DisclosurePanel,
    },
    props: {
        title: {
            type: String,
            required: true,
        },
        open: {
            type: Boolean,
            default: false,
        },
        checked: {
            type: [Number, String, Boolean, Object],
            default: null,
        },
        warn: {
            type: Boolean,
            default: true,
        },
        classes: {
            type: String,
            default: 'bg-gray-50 border-t border-gray-200'
        }
    },
};
</script>
