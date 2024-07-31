<?php

namespace app\Trait;

use Illuminate\Pagination\LengthAwarePaginator;

trait JsonResponse
{
    protected function paginatedResponse(LengthAwarePaginator $paginator, $resource, $status = 200)
    {
        return response()->json([
            'data' => $resource::collection($paginator),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'from' => $paginator->firstItem(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'to' => $paginator->lastItem(),
                'total' => $paginator->total(),
            ],
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
            'status' => $status
        ]);
    }
}