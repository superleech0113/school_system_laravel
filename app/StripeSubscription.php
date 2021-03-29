<?php

namespace App;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;

class StripeSubscription extends Model
{
    public const ERROR_STATUSES = [
        'incomplete',
        'past_due'
    ];

    public const END_STATUSES = [
        'incomplete_expired',
        'canceled'
    ];

    protected $appends = ['local_created_at', 'local_updated_at', 'error'];

    public function stripeSubscriptionPlanItems()
    {
        return $this->hasMany('\App\StripeSubscriptionPlanItem', 'stripe_subscription_id' , 'id');
    }

    public function user()
    {
        return $this->belongsTo('\App\User', 'user_id', 'id');
    }

    public function discount()
    {
        return $this->hasOne('\App\Discount', 'id', 'discount_id');
    }

    public function getLocalCreatedAtAttribute()
    {
        return \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $this->created_at, 'UTC')->setTimezone(CommonHelper::getSchoolTimezone())->format('Y-m-d H:i:s');
    }

    public function getLocalUpdatedAtAttribute()
    {
        return \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $this->updated_at, 'UTC')->setTimezone(CommonHelper::getSchoolTimezone())->format('Y-m-d H:i:s');
    }

    public function scopeHavingError($query)
    {
        return $query->whereIn('status', self::ERROR_STATUSES);
    }

    public function scopeCustomerMightBeChargedByStripe($query)
    {
        return $query->whereNotIn('status', self::END_STATUSES);
    }

    public function getErrorAttribute()
    {
        if(in_array($this->status, self::ERROR_STATUSES))
        {
            if($this->payment_intent_status == 'requires_payment_method')
            {
                return __('messages.default-card-details-needs-to-be-updated');
            }
            else
            {
                return $this->payment_intent_status;
            }
        }
        return null;
    }

    public function retryCharge()
    {
        try {
            $stripe_secret_key = Settings::get_value('stripe_secret_key');
            \Stripe\Stripe::setApiKey($stripe_secret_key);
            $stripeSubscription = \Stripe\Subscription::retrieve([ 'id' => $this->stripe_subscription_id ]);

            $stripe = new \Stripe\StripeClient($stripe_secret_key);
            $stripe->invoices->pay(
                $stripeSubscription->latest_invoice,
                [
                    'off_session' => true,
                ]
            );
            return [
                'status' => 1,
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return [
                'status' => 0,
                'message' => __('messages.stripe-error').": ".$e->getMessage()
            ];
        }
    }
}
