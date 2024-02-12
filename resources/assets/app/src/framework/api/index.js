import axios from 'axios';
import {useStore} from '@/framework/store';

const apiBaseURL = import.meta.env.VITE_API_BASE_URL;

let api = axios.create({
    baseURL: apiBaseURL,
    withCredentials: true,
    withXSRFToken: true,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
    validateStatus: function (status) {
        return status <= 210;
    },
});

api.interceptors.response.use(
    response => {
        if (response.message?.length) {
            const store = useStore();

            store.setFlashMessage({
                text: response.message,
                type: 'success',
            });
        }

        return response.data;
    },
    error => {

        if (error.response?.status === 419) {
            window.location.reload();
        }

        if (!window.location.href.toLowerCase().includes('verified') && error.response?.status === 403) {
            const message = error.response.data.message;
            if (message.toLowerCase().includes('verified')) {
                window.location = '/auth/verified';
            }
        }

        if (error.response?.status === 422) {
            const store = useStore();
            store.setErrors(error.response.data.errors);
            store.setFlashMessage({
                text: error.response.data.message,
                type: 'error',
            });
        }

        if (error.response?.status === 500) {
            const store = useStore();
            store.setFlashMessage({
                text: error.response.statusText,
                type: 'error',
            });
        }

        return Promise.reject(error);
    }
);

export default api;
