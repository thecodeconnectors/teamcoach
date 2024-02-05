import api from '@/framework/api';

export function getAvatars() {
    return api.get('avatars');
}
