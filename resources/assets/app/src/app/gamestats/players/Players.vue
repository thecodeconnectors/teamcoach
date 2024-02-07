<template>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Players</h1>
    </div>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8 md:pt-6">
        <div class="flex items-center mb-6 w-full">
            <Search :debounce-timeout="400" @onSearch="doSearch" class="mr-auto self-start" />
            <ButtonLink link-text="Add" link-url="/players/create" icon="plus-circle" />
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
import {getPlayers} from './players.api.js';
import Search from '@/framework/components/common/search/Search.vue';
import ButtonLink from '@/framework/components/common/button-link/ButtonLink.vue';
import CustomTable from '@/framework/components/common/table/CustomTable.vue';

const router = useRouter();
const table = reactive({
    isLoading: false,
    columns: [
        {
            field: 'name',
            label: 'Name',
            sortable: true,
            displayValue: row => `<span class="w-full flex items-left items-center"><img src="${row.profile_picture}" alt="${row.name}" width="32" class="mr-3 bg-blue-600 border-white border-2 rounded-full shadow" /> <span>${row.name}</span></span>`,
        },
        {
            field: 'position',
            label: 'Position',
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
        const {data: players, meta: {total, page, per_page}} = await getPlayers(params);
        table.rows = players;
        table.totalCount = total;
        table.pagination = {page, per_page};
    } catch (error) {
        console.error(error);
    } finally {
        table.isLoading = false;
    }
};

const navigateTo = ({id}) => {
    router.push(`/players/${id}`);
};

const doFetch = (params) => getData(fetchData, params);
const doSearch = (query) => searchData(fetchData, query);

fetchData();
</script>
