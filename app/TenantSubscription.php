<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TenantSubscription extends Model
{
    public const TENANT_NOT_CREATED = 0;
    public const CREATING_TENANT = 1;
    public const TENANT_CREATED = 2;

    public const SUBSCRIPTION_ACTIVE = 1;
    public const SUBSCRIPTION_INACTIVE = 0;

    public $incrementing = false;
    
    public function tenant()
    {
        return $this->belongsTo('App\Tenant', 'tenant_id', 'id');
    }
}
