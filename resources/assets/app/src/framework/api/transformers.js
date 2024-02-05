import {isObject, isArray, toSnakeCase, toCamelCase} from '@/framework/helpers';

export function transformToSnakeCase(data) {
    if (isObject(data)) {
        const obj = {};

        for (const [key, value] of Object.entries(data)) {
            obj[toSnakeCase(key)] = transformToSnakeCase(value);
        }

        return obj;
    }

    if (isArray(data)) {
        return data.map(item => transformToSnakeCase(item));
    }

    return data;
}

export function transformToCamelCase(data) {
    if (isObject(data)) {
        const obj = {};

        for (const [key, value] of Object.entries(data)) {
            obj[toCamelCase(key)] = transformToCamelCase(value);
        }

        return obj;
    }

    if (isArray(data)) {
        return data.map(item => transformToCamelCase(item));
    }

    return data;
}
