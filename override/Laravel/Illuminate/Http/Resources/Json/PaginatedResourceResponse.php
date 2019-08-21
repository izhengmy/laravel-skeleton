<?php

namespace Override\Laravel\Illuminate\Http\Resources\Json;

use Illuminate\Http\Resources\Json\PaginatedResourceResponse as BasePaginatedResourceResponse;
use Illuminate\Support\Str;

class PaginatedResourceResponse extends BasePaginatedResourceResponse
{
    /**
     * Gather the meta data for the response.
     *
     * @param  array  $paginated
     * @return array
     */
    protected function meta($paginated)
    {
        $paginated = parent::meta($paginated);
        $newPaginated = [];

        foreach ($paginated as $key => $value) {
            $newPaginated[Str::camel($key)] = $value;
        }

        return $newPaginated;
    }
}
