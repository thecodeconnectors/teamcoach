<?php

namespace App\Http\Middleware;

class ApiResourceCollectionCleanup
{
    public function handle($request, callable $next)
    {
        $response = $next($request);

        if (method_exists($response, 'getData')) {
            $response->setData(tap($response->getData(), function ($data) {

                // data.meta.links is unused, but so is data.links probably.
                if (isset($data->meta, $data->meta->links)) {
                    unset($data->meta->links);
                }

                if (isset($data->links, $data->links)) {
                    unset($data->links);
                }

                if (isset($data->meta, $data->meta->current_page)) {
                    $data->meta->page = $data->meta->current_page;

                    unset($data->meta->current_page);
                }

                foreach ([
                    'page',
                    'per_page',
                    'last_page',
                    'from',
                    'to',
                    'total',
                ] as $castNumeric) {
                    if (isset($data->meta, $data->meta->{$castNumeric}) && is_numeric($data->meta->{$castNumeric})) {
                        $data->meta->{$castNumeric} = (int)$data->meta->{$castNumeric};
                    }
                }
            }));
        }

        return $response;
    }
}
