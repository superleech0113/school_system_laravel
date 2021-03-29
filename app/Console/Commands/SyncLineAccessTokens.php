<?php

namespace App\Console\Commands;

use App\Helpers\LineHelper;
use App\Settings;
use Illuminate\Console\Command;
use Stancl\Tenancy\Traits\HasATenantsOption;
use Stancl\Tenancy\Traits\TenantAwareCommand;

class SyncLineAccessTokens extends Command
{
    use TenantAwareCommand, HasATenantsOption;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync_line_access_tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or delete line channel access tokens if required';

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
        $use_line_messaging_api = Settings::get_value('use_line_messaging_api');
        if ($use_line_messaging_api) 
        {
            $res = LineHelper::syncChannelAccessTokens();
            if ($res['status'] != 1) 
            {
                \Log::error('Error while syncing line access token: '. $res['message']);
            }
        }
    }
}
