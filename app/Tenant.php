<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    public $incrementing = false;

    public function mainDomain()
    {
        return $this->hasOne('App\Domain', 'tenant_id', 'id')->where('is_external', 0);
    }

    public function externalDomain()
    {
        return $this->hasOne('App\Domain', 'tenant_id', 'id')->where('is_external', 1);
    }

    public function getActiveDomain()
    {
        return $this->externalDomain ? $this->externalDomain : $this->mainDomain;
    }

    public function tenantSubscription()
    {
        return $this->hasOne('App\TenantSubscription', 'tenant_id', 'id');
    }
}
