import api from '@/framework/api';

export function uploadFile(payload) {
    // Used for the TinyMce uploads.
    return api.post('upload', payload);
}

export function uploadFileToMediaLibrary(payload) {
    // Used for the Medialibrary upload replacement.
    return api.post('media-library-pro/uploads', payload);
}
