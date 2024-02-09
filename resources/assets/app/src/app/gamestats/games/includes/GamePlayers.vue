<template>
    <table class="w-full text-gray-600">
        <thead>
        <tr>
            <th colspan="3" class="py-2 text-left font-normal text-gray-800 bg-gray-50">Players</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="player in playing" :key="player.id" :value="player.id">
            <td class="py-2">
                <span class="w-full flex items-left items-center">
                     <ProfilePicture :src="player.profile_picture" :alt="player.name" width="28" />
                     <span class="ml-1 mr-2">{{ player.name }}</span>
                     <Goals :events="player.events" />
                     <Cards :events="player.events" />
                </span>
            </td>
            <td class="text-right">
                <LiveSecondsToTimeString :enabled="state.timersEnabled" :seconds="player.playtime" class="text-xs" />
            </td>
            <td class="text-right object-right">
                <Icon v-if="editable" name="refresh" @click="openSubstiteMenu(player)" size="sm" class="rounded-full text-blue-600 p-1 mr-3" />
                <Icon name="chart-simple" @click="openPlayerEventList(player)" size="sm" class="rounded-full text-white bg-blue-600 p-1 mr-3" />
                <Icon v-if="editable" name="plus" @click="openPlayerMenu(player)" size="sm" class="rounded-full text-white bg-blue-600 p-1" />
            </td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <th colspan="3" class="py-2 text-left font-normal text-gray-800 bg-gray-50">Substitutes</th>
        </tr>
        <tr v-for="player in substitutes" :key="player.id" :value="player.id">
            <td class="py-2">
                <span class="w-full flex items-left items-center">
                    <ProfilePicture :src="player.profile_picture" :alt="player.name" width="28" />
                    <span class="ml-1 mr-2">{{ player.name }}</span>
                    <Goals :events="player.events" />
                    <Cards :events="player.events" />
                </span>
            </td>
            <td class="text-right">
                <LiveSecondsToTimeString :enabled="false" :seconds="player.playtime" class="text-xs" />
            </td>
            <td class="text-right object-right">
                <Icon name="chart-simple" @click="openPlayerEventList(player)" size="sm" class="rounded-full text-white bg-blue-600 p-1 mr-3" />
                <Icon v-if="editable" name="plus" @click="openPlayerMenu(player)" size="sm" class="rounded-full text-white bg-blue-600 p-1" />
            </td>
        </tr>
        </tfoot>
    </table>
    <SlideOver :title="`Activity ${state.showPlayerEventList?.name}`"
               :open="state.showPlayerEventList !== null"
               @close="state.showPlayerEventList = null"
               width-class="w-screen max-w-2xl">
        <PlayerEvents v-if="state.showPlayerEventList" :player="state.showPlayerEventList" />
    </SlideOver>
</template>
<script setup>
import Icon from '@/framework/components/common/icon/Icon.vue';
import Cards from '@/app/gamestats/Cards.vue';
import ProfilePicture from '@/app/gamestats/players/ProfilePicture.vue';
import Goals from '@/app/gamestats/Goals.vue';
import LiveSecondsToTimeString from '@/app/gamestats/LiveSecondsToTimeString.vue';
import {reactive} from 'vue';
import SlideOver from '@/framework/components/common/modals/SlideOver.vue';
import PlayerEvents from '@/app/gamestats/games/includes/PlayerEvents.vue';

const emit = defineEmits(['openSubstiteMenu', 'openPlayerEventList', 'openPlayerMenu']);

const props = defineProps({
    playing: {
        type: [Object, Array],
        default: [],
    },
    substitutes: {
        type: [Object, Array],
        default: [],
    },
    editable: {
        type: Boolean,
        default: false,
    },
});

const state = reactive({
    showPlayerEventList: null,
});

const openPlayerEventList = (player) => {
    state.showPlayerEventList = player;
};

const openSubstiteMenu = (player) => {
    emit('openSubstiteMenu', player);
};

const openPlayerMenu = (player) => {
    emit('openPlayerMenu', player);
};

</script>
