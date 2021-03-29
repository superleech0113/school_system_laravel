<?php

namespace App\Providers;

use App\Tenant as AppTenant;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\TenantManager;
use Stancl\Tenancy\Middleware\InitializeTenancy;
use Stancl\Tenancy\Middleware\PreventAccessFromTenantDomains;
use Stancl\Tenancy\Tenant;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Schema::defaultStringLength(191);

        \Validator::extendImplicit('current_password', function($attribute, $value, $parameters, $validator){
            return \Hash::check($value, auth()->user()->password);
        });

        \Validator::extend('uniqueFiledandModel', function ($attribute, $value, $parameters, $validator) {
            $field = \DB::table('custom_fields')->where('field_name', $value)
                                        ->where('data_model', $parameters[0]);
            if (!empty($parameters[1])) {
                $field->where('id', '!=', $parameters[1]);
            }
            $count = $field->count();
        
            return $count === 0;
        });
        
        tenancy()->hook('bootstrapping', function (TenantManager $tenantManager, Tenant $tenant) {
            $tenant_id = $tenantManager->getTenant('id');

            $tenantRecord = AppTenant::find($tenant_id);
            $tenant->activeDomain = $tenantRecord->getActiveDomain();
            $tenant->tenantSubscription = $tenantRecord->tenantSubscription;
        });
        
        tenancy()->hook('bootstrapped', function (TenantManager $tenantManager, Tenant $tenant) {
            \Spatie\Permission\PermissionRegistrar::$cacheKey = 'spatie.permission.cache.tenant.' . $tenantManager->getTenant('id');

            // Seprate session folder for each tenant
            config([
                'session.files' => storage_path('framework/sessions')
            ]);
            if (app()->runningInConsole()) {
                URL::forceRootUrl("https://".$tenant->activeDomain->domain);
            }
        });

        tenancy()->hook('ended', function (TenantManager $tenantManager) {
            // As tenancy is now ended change session file path to normal
            config([
                'session.files' => storage_path('framework/sessions')
            ]);
            if (app()->runningInConsole()) {
                URL::forceRootUrl("https://".explode(",",env('TENANCY_EXEMPT_DOMAINS'))[0]);
            }
        });

        $this->app->bind(InitializeTenancy::class, function ($app) {
            return new InitializeTenancy(function ($exception, $request, $next) {
                return response()->view('errors.custom-error', [
                    'message' => 'Domain not linked with any site'
                ]);
            });
        });

        $this->app->bind(PreventAccessFromTenantDomains::class, function ($app) {
            return new PreventAccessFromTenantDomains(function ($request, $next) {
                return redirect()->route('central.home');
            });
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
