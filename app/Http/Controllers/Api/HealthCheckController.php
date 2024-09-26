<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class HealthCheckController extends Controller
{
    /**
     * HealthCheck
     *
     */
    public function __invoke(): JsonResponse
    {
        return response()->json([Carbon::now()]);
    }
}
