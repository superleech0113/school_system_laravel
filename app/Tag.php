<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';
    protected $appends = ['display_name'];
    
    public const UPCOMMING_BIRTHDAY = 'Upcomming Birthday';
    public const NEW_STUDENT = 'New Student';
    public const DUE_TODO = 'Due Todo';
    public const LONG_TIME_STUDENT_1 = 'Long Time Student - 1+ Year';
    public const LONG_TIME_STUDENT_2 = 'Long Time Student - 2+ Year';
    public const LONG_TIME_STUDENT_3 = 'Long Time Student - 3+ Year';
    public const LONG_TIME_STUDENT_4 = 'Long Time Student - 4+ Year';
    public const LONG_TIME_STUDENT_5 = 'Long Time Student - 5+ Year';
    public const OUTSTANDING_PAYMENT = 'Oustanding Payment';
    public const RFID_REGISTERED = 'RFID Registered';
    public const LINE_CONNECTED = 'LINE Connected';
    public const STRIPE_SUBSCRIPTION_ERROR = 'Stripe Subscription Error';
    
    public function scopeOnlyAutomated($query)
    {
        return $query->where('is_automated',1);
    }

    public function getDisplayNameAttribute()
    {
        if ($this->is_automated) 
        {
            return __('messages.'.$this->name);
        } 
        else 
        {
            return $this->name;
        }
    }
}
