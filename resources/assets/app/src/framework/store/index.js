import {defineStore} from 'pinia';

export const useStore = defineStore('main', {
    state: () => ({
        sessionStarted: false,
        logo: '/src/assets/logo.svg',
        user: null,
        flashMessage: null,
        alertMessage: '',
        errors: null,
        toasts: [],
        notifications: [],
        frontendDomain: null,
    }),
    getters: {
        isAuthenticated(state) {
            return state.user !== null;
        },
    },
    actions: {
        setSessionStarted() {
            this.sessionStarted = true;
        },
        setUser(user) {
            this.user = user;
        },
        clearUser() {
            this.user = null;
        },
        frontendDomain(frontendDomain) {
            this.frontendDomain = frontendDomain;
        },
        setErrors(errors) {
            this.errors = errors;
        },
        setFlashMessage(flashMessage) {
            this.flashMessage = flashMessage;
        },
        setNotifications(notifications) {
            this.notifications = notifications;
        },
        setAlertMessage(alertMessage) {
            this.alertMessage = alertMessage;
        },
        addToastMessage(toast) {
            this.toasts.push(toast);
        },
        dismissToastMessage(index) {
            this.toasts.splice(index, 1);
        },
    },
});
