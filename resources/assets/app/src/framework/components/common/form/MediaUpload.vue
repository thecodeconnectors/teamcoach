<template>
    <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700">
            {{ label }}
        </label>
        <div class="mt-1">
            <img v-if="previewUrl" :src="previewUrl">

            <div class="w-full h-16 border border-grey-800 border-dashed flex items-start">
                <div class="w-16 h-16">
                    <img v-if="selectedFile" :src="selectedFileUrl" class="w-16 h-16">
                    <label v-else :for="name" class="cursor-pointer block p-5">
                        <Icon name="plus-circle" class="text-blue-500 h-4 w-4" />
                    </label>
                </div>
                <span v-if="selectedFile" @click="onCancel" class="p-6 text-center cursor-pointer">Cancel</span>
                <input :id="name" type="file" @change="onFileSelected" hidden>
            </div>
        </div>
    </div>
</template>

<script>
import {uploadFileToMediaLibrary} from '@/framework/components/common/media/media.api';
import {v4 as uuidv4} from 'uuid';
import Icon from '@/framework/components/common/icon/Icon.vue';

export default {
    name: 'MediaUpload',
    components: {Icon},
    props: {
        name: {
            type: String,
            required: true,
        },
        label: {
            type: String,
            required: false,
        },
        image: {
            type: Object,
            required: false,
        },
    },
    data() {
        return {
            hover: false,
            originalUrl: null,
            selectedFile: null,
        };
    },
    computed: {
        selectedFileUrl() {
            return this.selectedFile ? URL.createObjectURL(this.selectedFile) : null;
        },
        previewUrl() {
            let fullUrl = this.image?.full_url;
            if (!fullUrl) {
                const uuid = Object.keys(this.image)[0];
                fullUrl = this.image[uuid]?.original_url;
            }

            return fullUrl;
        }
    },
    methods: {
        onCancel() {
            this.selectedFile = null;
        },
        onFileSelected(event) {
            this.selectedFile = event.target.files[0];
            this.uploadTemporaryFile(this.selectedFile);
        },

        async uploadTemporaryFile(file) {
            const payload = new FormData();
            payload.append('file', file);
            payload.append('name', file.name);
            payload.append('uuid', uuidv4());

            try {
                const upload = await uploadFileToMediaLibrary(payload);
                this.$emit('change', [upload]);
            } catch (e) {
                console.log(e);
            }
        }
    },
    emits: [
        'change',
    ],
};
</script>
