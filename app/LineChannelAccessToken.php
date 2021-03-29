<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LineChannelAccessToken extends Model
{
    public function scopeNotExpiringSoon($query)
    {
        return $query->where('expires_at', '>' , Carbon::now('UTC')->addDays(2)->format('Y-m-d H:i:s'));
    }

    public function scopeExpiringSoon($query)
    {
        return $query->where('expires_at', '<=' , Carbon::now('UTC')->addDays(2)->format('Y-m-d H:i:s'));
    }
}