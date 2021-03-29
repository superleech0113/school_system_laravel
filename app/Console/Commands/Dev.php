<?php

namespace App\Console\Commands;

use App\Helpers\AutomatedTagsHelper;
use App\Helpers\NotificationHelper;
use App\Helpers\PaymentHelper;
use App\Settings;
use App\StripeSubscription;
use App\Students;
use Illuminate\Console\Command;
use Stancl\Tenancy\Traits\HasATenantsOption;
use Stancl\Tenancy\Traits\TenantAwareCommand;

class Dev extends Command
{
    use TenantAwareCommand, HasATenantsOption;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run test code while development';

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
        // $stripe = new \Stripe\StripeClient(Settings::get_value('stripe_secret_key'));
        // $data = $stripe->subscriptions->retrieve('sub_HknmDSLvaElvEH',[]);
        // PaymentHelper::updateLocalSubscriptionData($data->toArray());

        // $automatedTagsHelper = new AutomatedTagsHelper(Students::find(2));
        // $automatedTagsHelper->refreshStripeSubscriptionErrorTag();

        //NotificationHelper::sendStripeSubscriptionRequiresNewPaymentMethodNotification(StripeSubscription::find(15));
    }
}
