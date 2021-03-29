<?php

namespace App\Helpers;

use App\Jobs\DeleteTenantFileSystem;
use App\TenantSubscription;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Stancl\Tenancy\Tenant;
use Stancl\Tenancy\UniqueIDGenerators\UUIDGenerator;

class TenantHelper {

    public function __construct()
    {

    }

    private function getTenantFileSystemRootFolder($tenant_id)
    {
        return storage_path().'/'.config('tenancy.filesystem.suffix_base').$tenant_id;
    }

    private function getTenantWritableDirectories($tenant_id)
    {
        $base_dir = $this->getTenantFileSystemRootFolder($tenant_id);
        return [
            $base_dir.'/framework/sessions',
            $base_dir.'/app/temp_uploads',
            $base_dir.'/app/public',
            $base_dir.'/app/public/books',
            $base_dir.'/app/public/lesson/video',
            $base_dir.'/app/public/lesson/thumbnail',
            $base_dir.'/app/public/lesson_files',
            $base_dir.'/app/public/student_paper_test_files',
            $base_dir.'/app/public/todo_files',
            $base_dir.'/app/public/students',
            $base_dir.'/app/public/course',
            $base_dir.'/app/public/schedule_files',
        ];
    }

    public function createTenant($subscription_id, $school_name, $school_initial, $subdomain, $super_admin_username, $super_admin_email, $super_admin_password)
    {
        try {
            $tenantSubscription = TenantSubscription::find($subscription_id);
            $tenantSubscription->status = TenantSubscription::CREATING_TENANT;
            $tenantSubscription->save();

            session()->put('seed_vars', [
                'school_name' => $school_name,
                'school_initial' => $school_initial,
                'subdomain' => $subdomain,
                'super_admin_username' => $super_admin_username,
                'super_admin_email' => $super_admin_email,
                'super_admin_password' => $super_admin_password
            ]);

            $domain = $this->getFullDomain($subdomain);
            $tenant_id = UUIDGenerator::generate([$domain]);

            // Create tenants' File system folders
            $writableDirectories = $this->getTenantWritableDirectories($tenant_id);

            foreach($writableDirectories as $folderPath) {
                $res = mkdir($folderPath, 0755, true);
                if (!$res) {
                   throw new Exception('Can not create direcotry '. $folderPath);
                }
            }
            
            // Create tenant's Database
            $tenant = Tenant::new()
                ->withId($tenant_id)
                ->withDomains($domain)
                //->withData(['plan' => 'free'])
                ->save();
            //$tenant_id = $tenant->id;
            
            session()->forget('seed_vars');

            Artisan::call('cache:clear');
            
            $tenantSubscription->tenant_id = $tenant_id;
            $tenantSubscription->status = TenantSubscription::TENANT_CREATED;
            $tenantSubscription->save();

            return [
                'status' => 1,
                'message' => 'Site created successfully, redirecting to site',
                'redirect' => 'https://'.$domain
            ];
        }
        catch(Exception $e) {
            \Log::error($e);
            return [
                'status' => 0,
                'message' => 'Something went wrong'
            ];
        }
    }

    // public function deleteTenant($tenant_id)
    // {
    //     try{
    //         $tenant = tenancy()->find($tenant_id);

    //         $tenantSubscription = TenantSubscription::where('tenant_id', $tenant_id)->first();
    //         $tenantSubscription->tenant_id = NULL;
    //         $tenantSubscription->status = TenantSubscription::TENANT_NOT_CREATED;
    //         $tenantSubscription->save();

    //         // Delete tenant's Database
    //         $res = $tenant->delete();
    //         if (!$res) {
    //             throw new Exception('Something went wrong while deleting tenant database');
    //         }

    //         // Delete tenants' File system folders
    //         // Need to disptach a job, can't delete it direcly from here, otherwise it throws session file path not found error
    //         // Adding 10 sec for safely passing this req
    //         DeleteTenantFileSystem::dispatch($this->getTenantFileSystemRootFolder($tenant_id))->delay(Carbon::now()->addSeconds(10));

    //         return [
    //             'status' => 1,
    //             'message' => 'Site deleted successfully'
    //         ];
    //     }
    //     catch(\Exception $e) {
    //         \Log::error($e);
    //         return [
    //             'status' => 0,
    //             'message' => 'Something went wrong'
    //         ];
    //     }
    // }

    public function getFullDomain($subdomain)
    {
        return $subdomain.'.'.env('TENANCY_BASE_DOMAIN');
    }

    public function canUseSubdomain($subdomain)
    {
        $domain = $this->getFullDomain($subdomain);

        // check if reserved
        $tenancy_exempt_domains = (array) explode(",", env('TENANCY_EXEMPT_DOMAINS'));
        if(in_array($domain, $tenancy_exempt_domains))
        {
            return false;
        }

        // check if already in db
        $exists = \DB::table('domains')->where('domain', $domain)->exists();
        if ($exists)
        {
            return false;
        }

        return true;
    }
}

?>