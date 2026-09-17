<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    private const MAX_PER_PAGE = 100;

    /**
     * Page size requested through ?row= or ?perPage=, capped so a single
     * request can't pull an unbounded number of rows.
     */
    protected function perPage(Request $request): int
    {
        $perPage = $request->integer('row')
            ?: $request->integer('perPage')
            ?: (int) config('app.pagination')
            ?: 10;

        return min(max($perPage, 1), self::MAX_PER_PAGE);
    }
}
