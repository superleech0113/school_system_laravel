<?php

namespace App\Http\Controllers;

use App\Helpers\TenantHelper;
use App\TenantSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TenantController extends Controller
{
    public function navigateTenant($subscription_id, Request $request)
    {
        $tenantSubscription = TenantSubscription::findOrFail($subscription_id);

        if($tenantSubscription->status == TenantSubscription::TENANT_CREATED)
        {
            $activeDomain = $tenantSubscription->tenant->getActiveDomain();
            $protocol = $request->secure() ? 'https://' : 'http://';
            return redirect($protocol.$activeDomain->domain);
        }
        else if ($tenantSubscription->status == TenantSubscription::CREATING_TENANT)
        {
            return view('errors.custom-error', [
                'message' => 'Your site is being created, please try after some time'
            ]);
        }
        else
        {
            if ($tenantSubscription->subscription_status != TenantSubscription::SUBSCRIPTION_ACTIVE){
                abort(503, 'Subscription is not active');
            }
            
            return view('central.create_tenant', [
                'subscription_id' => $subscription_id
            ]);
        }
    }

    public function store(Request $request, $subscription_id)
    {
        $tenantSubscription = TenantSubscription::findOrFail($subscription_id);

        if ($tenantSubscription->subscription_status != TenantSubscription::SUBSCRIPTION_ACTIVE) {
            abort(503, 'Subscription is not active');
        }

        if($tenantSubscription->status != TenantSubscription::TENANT_NOT_CREATED){
            abort(401, 'Your site is being created or already created, can\'t create new site');
        }

        $request->validate([
            'school_name' => 'required',
            'school_initial' => 'required',
            'subdomain' => 'required|min:3|max:50|regex:/^[a-zA-Z0-9][a-zA-Z0-9\-]+[a-zA-Z0-9]$/i',
            'super_admin_username' => 'required|alpha_dash',
            'super_admin_email' => 'required|email',
            'super_admin_password' => 'required|min:6|confirmed',
            'super_admin_password_confirmation' => 'required',
        ]);

        $tenantHelper = new TenantHelper();

        $subdomain = strtolower($request->subdomain);
        $res = $tenantHelper->canUseSubdomain($subdomain);
        if (!$res) {
            $res = [
                'errors' => [
                    'subdomain' => [
                        'subdomain is already in use, please use another one.'
                    ]
                ]
            ];
            return response()->json($res, 422);
        }
        
        $res = $tenantHelper->createTenant(
                    $subscription_id,
                    $request->school_name,
                    $request->school_initial,
                    $subdomain,
                    $request->super_admin_username,
                    $request->super_admin_email,
                    $request->super_admin_password
                );
        if($res['status'] == 1) {
            return response()->json($res, 201);
        } else {
            return response()->json($res, 500);
        }
    }

    // public function destroy()
    // {
    //     Auth::logout(); 
    //     Session::flush();
    //     $tenant = tenant();
    //     $tenant_id = $tenant->id;
    //     tenancy()->end();

    //     $tenantHelper = new TenantHelper();
    //     $res = $tenantHelper->deleteTenant($tenant_id);

    //     return response()->view('errors.custom-error', [
    //         'message' => $res['message']
    //     ]);
    // }
}
