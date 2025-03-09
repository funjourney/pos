<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

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

        $totalPages = $limit > 0 ? ceil($totalItems / $limit) : 1;
        $hasNext = $currentPage < $totalPages;
        $hasPrev = $currentPage > 1;

        $pagination = [
            'current_page' => $currentPage,
            'page_size' => $limit,
            'total_pages' => $totalPages,
            'total_items' => $totalItems,
            'has_next' => $hasNext,
            'has_prev' => $hasPrev,
        ];

        return ['data' => $data, 'pagination' => $pagination];
    }
}
