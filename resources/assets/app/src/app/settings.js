import {reactive} from 'vue';

export function settings(key) {
    const settings = reactive({});

    if (key && settings?.length) {
        return settings[key] ?? null;
    }

    return settings;
}

export function isEnabled(key) {
    let setting = settings(key);
    return typeof setting !== 'undefined' && parseInt(setting) !== 0;
}
