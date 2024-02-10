<template>
    <div class="sm:overflow-hidden">
        <dl class="lg:shadow lg:rounded-md lg:overflow-hidden bg-white divide-y divide-gray-100">
            <div class="py-6 grid grid-cols-2 sm:grid-cols-6 gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-900">
                    Game
                </dt>
                <dd class="text-sm leading-6 text-gray-700 sm:col-span-2">
                    {{ game.is_away_game ? 'Away game' : 'Home game' }}
                </dd>
                <dt class="text-sm font-medium text-gray-900">
                    Parts
                </dt>
                <dd class="text-sm leading-6 text-gray-700 sm:col-span-2">
                    {{ game.parts }}
                </dd>
            </div>
            <div class="py-6 grid grid-cols-2 sm:grid-cols-6 gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-900">
                    Part duration
                </dt>
                <dd class="text-sm leading-6 text-gray-700 sm:col-span-2">
                    {{ game.part_duration }} minutes
                </dd>
                <dt class="text-sm font-medium text-gray-900">
                    Break duration
                </dt>
                <dd class="text-sm leading-6 text-gray-700 sm:col-span-2">
                    {{ game.break_duration }} minutes
                </dd>
            </div>
            <div class="py-6 grid grid-cols-2 sm:grid-cols-6 gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-900">
                    {{ game.started_at ? 'Started' : 'Start' }}
                </dt>
                <dd class="text-sm leading-6 text-gray-700 sm:col-span-2">
                    {{ formatDate(game.started_at ? game.started_at : game.start_at) }}
                </dd>
                <dt class="text-sm font-medium text-gray-900">
                    Finished
                </dt>
                <dd class="text-sm leading-6 text-gray-700 sm:col-span-2">
                    {{ formatDate(game.finished_at) }}
                </dd>
            </div>
            <div class="py-6 grid grid-cols-2 sm:grid-cols-6 gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-900">
                    Total time
                </dt>
                <dd class="text-sm leading-6 text-gray-700 sm:col-span-2">
                    {{ game.total_time }}
                </dd>
            </div>
            <div class="py-6 grid grid-cols-2 sm:grid-cols-6 gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-900">
                    Break time
                </dt>
                <dd class="text-sm leading-6 text-gray-700 sm:col-span-2">
                    {{ game.break_time }}
                </dd>
            </div>

            <div class="py-6 grid grid-cols-2 sm:grid-cols-6 gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-900">
                    Played time
                </dt>
                <dd class="text-sm leading-6 text-gray-700 sm:col-span-2">
                    {{ game.played_time }}
                </dd>
            </div>
        </dl>

    </div>
</template>
<script setup>

import {formatDate} from '../../../../framework/helpers.js';

const emit = defineEmits(['click']);

const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
});

</script>
