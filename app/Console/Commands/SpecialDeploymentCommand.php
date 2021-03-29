<?php

namespace App\Console\Commands;

use App\Settings;
use App\User;
use Illuminate\Console\Command;
use Stancl\Tenancy\Traits\HasATenantsOption;
use Stancl\Tenancy\Traits\TenantAwareCommand;
class SpecialDeploymentCommand extends Command
{
    use TenantAwareCommand, HasATenantsOption;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onetime:special_deployment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run code specific to particular deployment';

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
        $this->call('sync_line_access_tokens');
    }
}
