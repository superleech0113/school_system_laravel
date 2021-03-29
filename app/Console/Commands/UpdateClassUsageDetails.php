<?php

namespace App\Console\Commands;

use App\Jobs\CalculateClassUsage;
use App\Students;
use Illuminate\Console\Command;
use Stancl\Tenancy\Traits\HasATenantsOption;
use Stancl\Tenancy\Traits\TenantAwareCommand;

class UpdateClassUsageDetails extends Command
{
    use TenantAwareCommand, HasATenantsOption;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'student:update_class_usage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Class Usage Details for first time';

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
        $students = Students::get();
        foreach($students as $student)
        {
            $customer_id = $student->id;
            CalculateClassUsage::dispatch($customer_id);
        }
    }
}
