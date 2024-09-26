<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenHasScope
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $scope): Response
    {
        if (!$request->user() || !$request->user()->tokenCan($scope)) {
            return response()->json(['message' => 'Unauthorized - Token does not have the required scope'], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
