<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Validate the access token
        $accessToken = $request->query('access_token');

        if (!$accessToken || $accessToken !== env('ACCESS_TOKEN')) {
            return response()->json(['error' => 'Invalid access token'], 401);
        }

        return $next($request);
    }
}
