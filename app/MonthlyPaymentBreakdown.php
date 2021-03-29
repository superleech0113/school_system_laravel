<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonthlyPaymentBreakdown extends Model
{
    public $timestamps = false;

    public function plan()
    {
        return $this->belongsTo('App\Plan', 'plan_id', 'id');
    }
}
