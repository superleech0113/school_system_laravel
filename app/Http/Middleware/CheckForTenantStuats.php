<?php

namespace App\Http\Middleware;

use App\TenantSubscription;
use Closure;
use Stancl\Tenancy\TenantManager;

class CheckForTenantStuats
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
        $tenant = tenant();
        if ($tenant)
        {
            $tenantSubscription = $tenant->tenantSubscription;
            if (!$tenantSubscription) {
                abort(503, 'Site is archived');
            }

            if ($tenantSubscription->subscription_status != TenantSubscription::SUBSCRIPTION_ACTIVE){
                abort(503, 'Site is archived');
            }

            if ($tenantSubscription->status != TenantSubscription::TENANT_CREATED){
                abort(503, 'Site is being created, please try after some time');
            }
        }
        return $next($request);
    }
}