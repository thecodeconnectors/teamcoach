<template>
    <table class="w-full text-gray-600">
        <thead v-if="!mergePlayers">
        <tr>
            <th colspan="3" class="py-2 text-left font-normal text-gray-800 bg-gray-50">Players</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="player in playersToShow" :key="player.id" :value="player.id">
            <td class="py-2">
                <span class="w-full flex items-left items-center">
                     <ProfilePicture :src="player.profile_picture" :alt="player.name" width="28" />
                     <span class="ml-1 mr-2">{{ player.name }}</span>
                     <Goals :events="player.events" />
                     <Cards :events="player.events" />
                </span>
            </td>
            <td class="text-right">
                <LiveSecondsToTimeString :enabled="timersEnabled" :seconds="player.playtime" class="text-xs" />
            </td>
            <td class="py-2">
                <div class="w-full flex flex-row-reverse items-center">
                    <Icon v-if="editable" name="plus" @click="openPlayerMenu(player)" size="sm" class="rounded-full text-white bg-blue-600 p-1" />
                    <Icon name="chart-simple" @click="openPlayerEventList(player)" size="sm" class="rounded-full text-white bg-blue-600 p-1 mr-3" />
                    <Icon v-if="editable" name="refresh" @click="openSwitchPlayerMenu(player)" size="sm" class="rounded-full text-blue-600 p-1 mr-3" />
                </div>
            </td>
        </tr>
        </tbody>
        <tfoot>
        <tr v-if="!mergePlayers">
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr v-if="!mergePlayers">
            <th colspan="3" class="py-2 text-left font-normal text-gray-800 bg-gray-50">Substitutes</th>
        </tr>
        <tr v-if="!mergePlayers" v-for="player in substitutes" :key="player.id" :value="player.id">
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
            <td class="py-2">
                <div class="w-full flex flex-row-reverse items-center">
                    <Icon v-if="editable" name="plus" @click="openPlayerMenu(player)" size="sm" class="rounded-full text-white bg-blue-600 p-1" />
                    <Icon name="chart-simple" @click="openPlayerEventList(player)" size="sm" class="rounded-full text-white bg-blue-600 p-1 mr-3" />
                    <Icon v-if="editable" name="refresh" @click="openSwitchPlayerMenu(player)" size="sm" class="rounded-full text-blue-600 p-1 mr-3" />
                </div>
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
import {computed, reactive} from 'vue';
import SlideOver from '@/framework/components/common/modals/SlideOver.vue';
import PlayerEvents from '@/app/gamestats/games/includes/PlayerEvents.vue';

const emit = defineEmits(['openSwitchPlayerMenu', 'openPlayerEventList', 'openPlayerMenu']);

const props = defineProps({
    players: {
        type: [Object, Array],
        default: [],
    },
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
    timersEnabled: {
        type: Boolean,
        default: false,
    },
    mergePlayers: {
        type: Boolean,
        default: false,
    }
});

const state = reactive({
    showPlayerEventList: null,
});

const playersToShow = computed(() => props.mergePlayers ? props.players : props.playing);

const openPlayerEventList = (player) => {
    state.showPlayerEventList = player;
};

const openSwitchPlayerMenu = (player) => {
    emit('openSwitchPlayerMenu', player);
};

const openPlayerMenu = (player) => {
    emit('openPlayerMenu', player);
};

</script>
