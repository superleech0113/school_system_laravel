<?php

namespace App\Http\Middleware;

use Closure;

class TenantApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('authorization') != env('TENANCY_API_KEY'))
        {
            return response()->json('Unauthorized', 401);
        }
        return $next($request);
    }
}
