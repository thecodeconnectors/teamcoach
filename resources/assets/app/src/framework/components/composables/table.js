export function useDataTable(tableOptions) {
    let search    = null;
    const getData = async (fetchData, {page, per_page, orderBy, sortDirection}) => {
        if (typeof fetchData !== 'function') {
            throw new Error('');
        }

        if (typeof search === 'string') {
            search = {
                q: search
            };
        }

        await fetchData({page, per_page, sort: `${orderBy}:${sortDirection}`, ...search});

        tableOptions.sorting.orderBy       = orderBy;
        tableOptions.sorting.sortDirection = sortDirection;
    };

    const searchData = async (fetchData, query) => {
        search = query || null;

        if (typeof search === 'string') {
            search = {
                q: search
            };
        }

        await fetchData({
            ...tableOptions.pagination,
            ...{sort: `${tableOptions.sorting.orderBy}:${tableOptions.sorting.sortDirection}`},
            ...search
        });
    };

    return {
        getData,
        searchData,
    };
}
