import {useRouter} from 'vue-router';

export function debounce(func, timeout = 300) {
    let timer;

    return (...args) => {
        window.clearTimeout(timer);
        timer = window.setTimeout(() => {
            func(...args);
        }, timeout);
    };
}

export function isObject(value) {
    return value != null && value.constructor.name === 'Object';
}

export function isArray(value) {
    return Array.isArray(value);
}

export function toCamelCase(string) {
    return string.split('_')
        .map((word, index) => index === 0 ? word : `${word.charAt(0).toUpperCase()}${word.slice(1)}`)
        .join('');
}

export function toSnakeCase(string) {
    return string.split(/(?=[A-Z])/).join('_').toLowerCase();
}

export function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes === 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

export function formatDate(date) {
    if (date) {
        const d = new Date(date);
        date    = d.toLocaleString('nl-NL', {year: '2-digit', month: 'numeric', day: 'numeric'})
            + ' ' + d.toLocaleTimeString('nl-NL', {hour12: false, hour: '2-digit', minute: '2-digit'});
    }
    return date;
}

export function findImageFromCollection(collection, collectionName) {
    const media = collection ? collection.filter(function (media) {
        return media.collection_name === collectionName;
    }) : [];

    return media[0] || {};
}

export function nl2br(string) {
    if (typeof string === 'undefined' || string === null) {
        return '';
    }

    return String(string).replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1<br>$2');
}

export function isCurrent(path) {
    const router = useRouter();
    return router.currentRoute.value.fullPath.startsWith(path);
}
