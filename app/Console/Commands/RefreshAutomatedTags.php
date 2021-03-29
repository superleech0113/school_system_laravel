<?php

namespace App\Console\Commands;

use App\Helpers\AutomatedTagsHelper;
use App\Settings;
use App\Students;
use App\TenantSubscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Stancl\Tenancy\Traits\HasATenantsOption;
use Stancl\Tenancy\Traits\TenantAwareCommand;

class RefreshAutomatedTags extends Command
{
    use TenantAwareCommand, HasATenantsOption;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'student:refresh_automated_tags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalulate logic to attach or detach automated tags for each student';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->specifyParameters();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Do not run this command if tenant subscription is not active
        $tenant = tenant();
        if (!(
            $tenant->tenantSubscription &&
            $tenant->tenantSubscription->status == TenantSubscription::TENANT_CREATED && 
            $tenant->tenantSubscription->subscription_status == TenantSubscription::SUBSCRIPTION_ACTIVE
        )){
            return;
        }

        $timezone = Settings::get_value('school_timezone');
        $now = Carbon::now($timezone)->format('H:i');   
        if($now != '00:00') // Should run at every mid night
        {
            return;
        }
        
        $students = Students::all();
        foreach($students as $student)
        {
            $automatedTagsHelper = new AutomatedTagsHelper($student);
            $automatedTagsHelper->refreshUpcommingBirthdayTag();
            $automatedTagsHelper->refreshNewStudentTag();
            $automatedTagsHelper->refreshDueTodoTag();
            $automatedTagsHelper->refreshLongTimeStudentTag();
        }
    }
}
