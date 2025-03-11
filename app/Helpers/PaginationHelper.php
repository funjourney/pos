<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PaginationHelper
{
    public static function createPaginateFromEloquent(Builder $query, int $limit, int $currentPage)
    {
        $totalItems = $query->count();

        if ($limit > 0) {
            $offset = ($currentPage - 1) * $limit;
            $data = $query->offset($offset)->limit($limit)->get();
        } else {
            $limit = $totalItems;
            $currentPage = 1;
            $data = $query->get();
        }

        // Convert snake_case keys to camelCase
        $data = $data->map(function ($item) {
            return collect($item->toArray())->mapWithKeys(function ($value, $key) {
                return [Str::camel($key) => $value];
            })->all();
        });

        $totalPages = $limit > 0 ? ceil($totalItems / $limit) : 1;
        $hasNext = $currentPage < $totalPages;
        $hasPrev = $currentPage > 1;

        $pagination = [
            'currentPage' => $currentPage,
            'pageSize' => $limit,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'hasNext' => $hasNext,
            'hasPrev' => $hasPrev,
        ];

        return ['data' => $data, 'pagination' => $pagination];
    }
}
