<template>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Trainings</h1>
    </div>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8 md:pt-6">
        <div class="flex items-center mb-6 w-full">
            <Search :debounce-timeout="400" @onSearch="doSearch" class="mr-auto self-start" />
            <ButtonLink v-if="hasPermission('training.create')" link-text="Add" link-url="/training/create" icon="plus-circle" />
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
import {getTrainings} from './training.api.js';
import Search from '@/framework/components/common/search/Search.vue';
import ButtonLink from '@/framework/components/common/button-link/ButtonLink.vue';
import CustomTable from '@/framework/components/common/table/CustomTable.vue';
import {useAuth} from '@/framework/composables/use-auth.js';
import {formatDate} from '@/framework/helpers.js';

const router = useRouter();

const {hasPermission} = useAuth();

const table = reactive({
    isLoading: false,
    columns: [
        {
            field: 'start_at',
            label: 'Start at',
            sortable: true,
            displayValue: row => formatDate(row.start_at)
        },
        {
            field: 'team_name',
            label: 'Team',
        },
    ],
    rows: [],
    totalCount: 0,
    pagination: {
        page: 1,
        per_page: 15,
    },
    sorting: {
        orderBy: 'start_at',
        sortDirection: 'asc',
    },
    interactive: true,
});

const {getData, searchData} = useDataTable(table);

const fetchData = async (params) => {
    table.isLoading = true;
    try {
        const {data: trainings, meta: {total, page, per_page}} = await getTrainings(params);
        table.rows = trainings;
        table.totalCount = total;
        table.pagination = {page, per_page};
    } catch (error) {
        console.error(error);
    } finally {
        table.isLoading = false;
    }
};

const navigateTo = ({id}) => {
    router.push(`/training/${id}`);
};

const doFetch = (params) => getData(fetchData, params);
const doSearch = (query) => searchData(fetchData, query);
fetchData();
</script>
