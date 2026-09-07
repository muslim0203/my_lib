<?php

namespace App\Core\Helpers;

use Illuminate\Http\Request;

class PageSizeHelper
{
    /**
     * @param Request $request
     * @return int|mixed
     */
    public static function getPageSize(Request $request): mixed
    {
        return $request->has('pageSize')
            ? ($request->get('pageSize', 20) > 100
                ? 100
                : (
                ($request->get('pageSize') <= 0)
                    ? 20
                    : $request->get('pageSize')
                )
            )
            : 20;
    }
}
