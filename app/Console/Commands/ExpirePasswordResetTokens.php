<?php

namespace App\Console\Commands;

use App\PasswordResetToken;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Stancl\Tenancy\Traits\HasATenantsOption;
use Stancl\Tenancy\Traits\TenantAwareCommand;

class ExpirePasswordResetTokens extends Command
{
    use TenantAwareCommand, HasATenantsOption;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expire:password_reset_tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove password reset tokens generated before an hour';

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
        PasswordResetToken::where('created_at', '<', Carbon::now()->subHour())->delete();
    }
}
