<?php

namespace App;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $appends = ['in_use_with_stripe'];

    public function monthlyPaymentBreakdowns()
    {
        return $this->hasMany('\App\MonthlyPaymentBreakdown', 'plan_id', 'id');
    }

    public function paymentBreakdownSettings()
    {
        return $this->hasMany('\App\PaymentBreakdownSetting', 'plan_id', 'id');
    }

    public function getInUseWithStripeAttribute()
    {
        return $this->stripe_product_id ? true : false;
    }

    public function createProductOnStripe()
    {
        $stripe = new \Stripe\StripeClient(Settings::get_value('stripe_secret_key'));
        $stripeProduct = $stripe->products->create([
            'name' => $this->name,
        ]);
        $this->stripe_product_id = $stripeProduct->id;
    }

    public function updateProductOnStripe()
    {
        $stripe = new \Stripe\StripeClient(Settings::get_value('stripe_secret_key'));
        $stripe->products->update(
            $this->stripe_product_id,
            ['name' => $this->name]
        );
    }

    public function createPriceOnStripe()
    {
        $stripe = new \Stripe\StripeClient(Settings::get_value('stripe_secret_key'));
        $currency = Settings::get_value('stripe_currency');
        $stripePrice = $stripe->prices->create([
            'currency' => $currency,
            'unit_amount' => CommonHelper::getStripeAmount($currency, $this->price_per_month),
            'recurring' => ['interval' => 'month'],
            'product' => $this->stripe_product_id
        ]);
        $this->stripe_price_id = $stripePrice->id;
    }

    public function syncWithStripe($send_to_stripe)
    {
        if (!$this->in_use_with_stripe && $send_to_stripe) 
        {
            $this->createProductOnStripe();
            $this->createPriceOnStripe();
        }
        else if($this->in_use_with_stripe)
        {
            if ($this->isDirty('name')) 
            {
                $this->updateProductOnStripe();
            }
        }
    }
}
