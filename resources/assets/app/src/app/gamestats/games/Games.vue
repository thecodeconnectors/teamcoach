<template>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Games</h1>
    </div>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8 md:pt-6">
        <div class="flex items-center mb-6 w-full">
            <Search :debounce-timeout="400" @onSearch="doSearch" class="mr-auto self-start" />
            <ButtonLink v-if="hasPermission('game.create')" link-text="Add" link-url="/games/create" icon="plus-circle" />
        </div>
        <CustomTable
            :columns="table.columns"
            :rows="table.rows"
            :total="table.totalCount"
            :sorting="table.sorting"
            :pagination="table.pagination"
            :interactive="table.interactive"
            :is-loading="table.isLoading"
            @doFetch="doFetch"
            @onSelect="navigateTo"
        />
    </div>
</template>

<script setup>
import {reactive} from 'vue';
import {useRouter} from 'vue-router';
import {useDataTable} from '@/framework/components/composables/table.js';
import {getGames} from './games.api.js';
import Search from '@/framework/components/common/search/Search.vue';
import ButtonLink from '@/framework/components/common/button-link/ButtonLink.vue';
import CustomTable from '@/framework/components/common/table/CustomTable.vue';
import {useAuth} from '@/framework/composables/use-auth.js';

const {hasPermission} = useAuth();
const router = useRouter();
const table = reactive({
    isLoading: false,
    columns: [
        {
            field: 'opponent_name',
            label: 'Opponent',
            sortable: true,
        },
        {
            field: 'start_at',
            label: 'Date',
            sortable: true,
        },
    ],
    rows: [],
    totalCount: 0,
    pagination: {
        page: 1,
        per_page: 15,
    },
    sorting: {
        orderBy: 'name',
        sortDirection: 'asc',
    },
    interactive: true,
});

const {getData, searchData} = useDataTable(table);

const fetchData = async (params) => {
    table.isLoading = true;
    try {
        const {data: games, meta: {total, page, per_page}} = await getGames(params);
        table.rows = games;
        table.totalCount = total;
        table.pagination = {page, per_page};
    } catch (error) {
        console.error(error);
    } finally {
        table.isLoading = false;
    }
};

const navigateTo = (game) => {
    if (game.started_at) {
        router.push(`/games/${game.id}/play`);
    } else {
        router.push(`/games/${game.id}`);
    }
};

const doFetch = (params) => getData(fetchData, params);
const doSearch = (query) => searchData(fetchData, query);

fetchData();
</script>
