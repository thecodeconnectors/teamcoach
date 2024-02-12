<template>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Users</h1>
    </div>
    <div class="max-w-full mx-auto px-4 sm:px-6 md:px-8 md:pt-6">
        <div class="flex items-center mb-6 w-full">
            <Search :debounce-timeout="400" @onSearch="doSearch" class="mr-auto self-start" />
            <ButtonLink v-if="hasPermission('team.create')" link-text="Add" link-url="/users/create" icon="plus-circle" />
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
import {getUsers} from './users.api.js';
import Search from '@/framework/components/common/search/Search.vue';
import ButtonLink from '@/framework/components/common/button-link/ButtonLink.vue';
import CustomTable from '@/framework/components/common/table/CustomTable.vue';
import {useAuth} from '@/framework/composables/use-auth.js';
import {formatDate} from '@/framework/helpers.js';

const {hasPermission} = useAuth();
const router = useRouter();
const table = reactive({
    isLoading: false,
    columns: [
        {
            field: 'name',
            label: 'Name',
            sortable: true,
        },
        {
            field: 'email',
            label: 'Email',
            sortable: true,
        },
        {
            field: 'email_verified_at',
            label: 'Email verified at',
            sortable: true,
            displayValue: row => formatDate(row.email_verified_at)
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
        const {data: users, meta: {total, page, per_page}} = await getUsers(params);
        table.rows = users;
        table.totalCount = total;
        table.pagination = {page, per_page};
    } catch (error) {
        console.error(error);
    } finally {
        table.isLoading = false;
    }
};

const navigateTo = ({id}) => {
    router.push(`/users/${id}`);
};

const doFetch = (params) => getData(fetchData, params);
const doSearch = (query) => searchData(fetchData, query);

fetchData();
</script>
