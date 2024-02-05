import api from "@/framework/api";

export function clearCache() {
    return api.post('cache/clear');
}
