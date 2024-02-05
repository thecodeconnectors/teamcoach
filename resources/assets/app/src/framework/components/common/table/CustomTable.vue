<template>
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                        <tr>
                            <th
                                v-for="col in visibleColumns"
                                :key="col.field"
                                scope="col"
                                class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                @click="col.sortable && doSort(col.field)"
                            >

                                <span v-if="col.field === 'checkbox'">
                                    <input type="checkbox" @click="onToggleAllCheckboxes" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded pointer">
                                </span>

                                <span :class="{'mr-2': col.sortable}">{{ col.label }}</span>
                                <Icon
                                    v-if="col.sortable && sorting.orderBy === col.field"
                                    :name="sorting.sortDirection === 'asc' ? 'sort-up' : 'sort-down'"
                                />
                                <Icon
                                    v-if="col.sortable && sorting.orderBy !== col.field"
                                    name="sort"
                                />
                            </th>
                        </tr>
                        </thead>
                        <tbody v-if="!isLoading" class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="(row, index) in rows"
                            :key="row[settings.keyColumn] || index"
                            :class="{'hover:cursor-pointer hover:bg-secondary-lightest': settings.interactive, 'pointer-events-none text-grey-dark': row.updateInProgress}"
                        >
                            <slot name="row" :row="row" :columns="columns">
                                <td
                                    v-for="{field, displayValue, type} in visibleColumns"
                                    :key="field"
                                    @click="settings.interactive && field !== 'checkbox' && onSelect(row)"
                                    class="px-3 py-3 whitespace-nowrap text-sm text-gray-500"
                                >
                                    <span v-if="field === 'checkbox'">
                                        <input type="checkbox" @change="onToggleCheckbox(row)" :checked="row.selected_by_table_checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded pointer">
                                    </span>
                                    <span v-else-if="displayValue" v-html="displayValue(row)">

                                    </span>
                                    <span v-else-if="type === 'checkmark'">
                                        <Icon prefix="far" name="check-circle" class="text-green-500" v-if="getValue(field, row)" />
                                        <Icon name="exclamation-circle" class="text-red-500" v-else />
                                    </span>

                                    <span v-else @click="settings.interactive && field !== 'checkbox' && onSelect(row)">{{ getValue(field, row) }}</span>
                                </td>
                            </slot>
                        </tr>
                        <tr v-show="!isLoading && !rows.length">
                            <td :colspan="columns.length" class="py-4 text-center">
                                No Results
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <TableLoader :isLoading="isLoading" />
                </div>
            </div>
        </div>
        <div v-if="rows.length" class="py-3 flex items-center justify-between pagination">
            <div class="flex-1 flex justify-between sm:hidden">
                <a @click="prevPage" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </a>
                <a @click="nextPage" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </a>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center justify-between w-max">
                        <DropDownSelect
                            :key="settings.pageSizeOptions.length"
                            v-model="settings.pagination.per_page"
                            :options="settings.pageSizeOptions"
                            :inline="true"
                            @onChange="changePageSize"
                        />
                        <p class="text-sm text-gray-700 ml-3">
                            Showing
                            <span class="font-medium">{{ settings.offset }}</span>
                            to
                            <span class="font-medium">{{ settings.limit }}</span>
                            of
                            <span class="font-medium">{{ total }}</span>
                            results
                        </p>
                    </div>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <a @click="prevPage" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Previous</span>
                            <Icon name="angle-left" class="mx-2" />
                        </a>

                        <a
                            v-for="pageNo in settings.paging"
                            :key="pageNo"
                            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                            :class="settings.pagination.page === pageNo ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
                            @click="goToPage(pageNo)">
                            {{ pageNo }}
                        </a>

                        <a @click="nextPage" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Next</span>
                            <Icon name="angle-right" class="mx-2" />
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {computed, reactive} from 'vue';
import {isObject} from '@/framework/helpers.js';
import TableLoader from '@/framework/components/common/loaders/TableLoader.vue';
import DropDownSelect from '@/framework/components/common/form/DropDownSelect.vue';
import Icon from '@/framework/components/common/icon/Icon.vue';

export default {
    name: 'CustomTable',
    components: {
        Icon,
        TableLoader,
        DropDownSelect,
    },
    props: {
        columns: {
            type: Array,
            required: true,
        },
        rows: {
            type: Array,
            required: true,
            validate(value) {
                const hasId = Boolean(value.id);

                if (!hasId) {
                    console.error('Every row item should have a unique id property!');
                }

                return hasId;
            },
        },
        total: {
            type: Number,
            required: true,
        },
        sorting: {
            type: Object,
            default() {
                return {
                    orderBy: 'title',
                    sortDirection: 'asc',
                };
            },
            validate(value) {
                const isValid = Object.values(value).every(prop => typeof prop === 'string' && prop !== '');

                if (!isValid) {
                    console.error('`orderBy` and `sortDirection` should be non-empty strings');
                }

                return isValid;
            },
        },
        pagination: {
            type: Object,
            default() {
                return {
                    per_page: 25,
                    page: 1,
                };
            },
            validate(value) {
                const isValid = Object.values(value).every(prop => Number.isInteger(prop));

                if (!isValid) {
                    console.error('`per_page` and `page` should be integers');
                }

                return isValid;
            },
        },
        isLoading: {
            type: Boolean,
            default: false,
        },
        interactive: {
            type: Boolean,
            default: false,
        },
    },

    emits: [
        'onSelect',
        'doFetch',
        'onSelectedRows',
    ],

    setup(props, {emit}) {
        const visibleColumns = reactive(props.columns.filter(column => !column.hidden));

        const getValue = (field, obj) => {
            const [key, ...restOfKeys] = field.split('.');
            const value = obj[key];

            return isObject(value) ? getValue(restOfKeys.join('.'), value) : value;
        };

        const settings = reactive({
            keyColumn: computed(() => {
                let key = '';

                props.columns.forEach(column => {
                    if (column.isKey) {
                        key = column.field;
                    }
                });

                return key;
            }),
            pagination: props.pagination,
            maxPage: computed(() => {
                if (props.total <= 0) {
                    return 0;
                }

                let maxPage = Math.floor(props.total / settings.pagination.per_page);

                const mod = props.total % settings.pagination.per_page;

                if (mod > 0) {
                    maxPage += 1;
                }

                return maxPage;
            }),
            maxPageOptions: computed(() => Array.from({length: settings.maxPage}, (_, index) => ({
                value: index + 1,
                text: index + 1,
                translated: true,
            }))),
            offset: computed(() => (settings.pagination.page - 1) * settings.pagination.per_page + 1),
            limit: computed(() => {
                const limit = settings.pagination.page * settings.pagination.per_page;

                return props.total >= limit ? limit : props.total;
            }),
            paging: computed(() => {
                const currentPage = Number(settings.pagination.page);
                let startPage = (currentPage - 1 <= 0) ? 1 : currentPage - 1;

                if (settings.maxPage - currentPage <= 1) {
                    startPage = settings.maxPage - 2;
                }

                startPage = startPage <= 0 ? 1 : startPage;

                const pages = [];

                for (let i = startPage; i <= settings.maxPage; i++) {
                    if (pages.length < 3) {
                        pages.push(i);
                    }
                }
                return pages;
            }),
            pageSizeOptions: computed(() => [15, 25, 50, 75, 100].map(size => ({
                value: size,
                text: size,
                translated: true,
            }))),
            interactive: props.interactive,
            messages: {
                pagingInfo: 'general.pagination.pagingInfo',
                pageSizeChangeLabel: 'general.pagination.pageSizeChangeLabel',
                gotoPageLabel: 'general.pagination.gotoPageLabel',
                noResults: 'general.pagination.noResults',
                ...props.messages,
            },
        });

        const doSort = orderBy => {
            let sortDirection = 'asc';

            if (orderBy === props.sorting.orderBy) {
                sortDirection = (props.sorting.sortDirection === 'asc') ? 'desc' : 'asc';
            }

            goToPage(1, true);

            emit('doFetch', {...settings.pagination, orderBy, sortDirection});
        };

        const nextPage = () => {
            if (settings.pagination.page >= settings.maxPage) {
                return;
            }

            settings.pagination.page += 1;

            changePage(settings.pagination.page);
        };

        const prevPage = () => {
            if (settings.pagination.page === 1) {
                return;
            }

            settings.pagination.page -= 1;

            changePage(settings.pagination.page);
        };

        const goToPage = (page, isResearch = false) => {
            settings.pagination.page = Number(page);

            changePage(settings.pagination.page, isResearch);
        };

        const changePage = (page, isResearch = false) => {
            const currentPage = Number(page);
            const {orderBy, sortDirection} = props.sorting;
            const {per_page} = settings.pagination;

            if (!isResearch || currentPage > 1) {
                emit('doFetch', {page: currentPage, per_page, orderBy, sortDirection});
            }
        };

        const changePageSize = per_page => {
            settings.pagination.per_page = per_page;

            if (settings.pagination.page === 1) {
                changePage(settings.pagination.page);
            } else {
                settings.pagination.page = 1;
            }
        };

        const onSelect = row => {
            emit('onSelect', row);
        };

        const selectedRows = computed(() => props.rows.filter(row => row.selected_by_table_checkbox));

        const selectRows = () => {
            emit('onSelectedRows', selectedRows.value);
        };

        const onToggleAllCheckboxes = (e) => {
            props.rows.forEach(row => {
                row.selected_by_table_checkbox = Boolean(e.target.checked);
            });

            selectRows();
        };

        const onToggleCheckbox = row => {
            row.selected_by_table_checkbox = !row.selected_by_table_checkbox;
            selectRows();
        };

        return {
            visibleColumns,
            settings,
            getValue,
            doSort,
            nextPage,
            prevPage,
            goToPage,
            onSelect,
            changePageSize,
            onToggleAllCheckboxes,
            onToggleCheckbox,
        };
    },
};
</script>

<style scoped>
tbody tr, thead tr, .pagination a {
    cursor: pointer;
}

tbody tr:hover {
    @apply bg-gray-100;
}
</style>
