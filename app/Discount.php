<?php

namespace App;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $appends = ['in_use_with_stripe', 'display_duration'];

    public const DURATION_FOREVER = 0;
    public const DURATION_ONCE = 1;

    public static function durationEnum()
    {
        return [
            'forever' => self::DURATION_FOREVER,
            'once' => self::DURATION_ONCE
        ];
    }

    public function getDisplayDurationAttribute() 
    {
        $text = '';
        switch($this->duration) {
            case self::DURATION_FOREVER:
                $text = 'Forever';
                break;
            case self::DURATION_ONCE:
                $text = 'Once';
                break;
        }
        return $text;
    }

    public function getStripeDuration() 
    {
        $text = '';
        switch($this->duration) {
            case self::DURATION_FOREVER:
                $text = 'forever';
                break;
            case self::DURATION_ONCE:
                $text = 'once';
                break;
        }
        return $text;
    }

    public function getInUseWithStripeAttribute()
    {
        return $this->stripe_coupon_id ? true : false;
    }

    public function createCoupenOnStripe()
    {
        $stripe = new \Stripe\StripeClient(Settings::get_value('stripe_secret_key'));
        $currency = Settings::get_value('stripe_currency');
        $stripeCoupon = $stripe->coupons->create([
            'name' => $this->name,
            'duration' => $this->getStripeDuration(),
            'amount_off' => CommonHelper::getStripeAmount($currency, $this->amount),
            'currency' => $currency,
        ]);
        $this->stripe_coupon_id = $stripeCoupon->id;
    }

    public function updateCoupenOnStripe()
    {
        $stripe = new \Stripe\StripeClient(Settings::get_value('stripe_secret_key'));
        $stripe->coupons->update(
            $this->stripe_coupon_id,
            [ 'name' => $this->name ]
        );
    }

    public function syncWithStripe($send_to_stripe)
    {
        if (!$this->in_use_with_stripe && $send_to_stripe) 
        {
            $this->createCoupenOnStripe();
        }
        else if($this->in_use_with_stripe)
        {
            if ($this->isDirty('name')) 
            {
                $this->updateCoupenOnStripe();
            }
        }
    }
}
