<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;

class ApiController extends Controller
{
    use ApiResponses;

    /**
     * Determine whether the given relationship was requested via the include parameter.
     */
    public function include(string $relationship) : bool
    {
        $param = request()->get('include');

        if (!isset($param)) {
            return false;
        }

        $includeValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includeValues);
    }
}
