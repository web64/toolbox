<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ipaddress extends Model
{
    protected $guarded = [];


    public function records()
    {
        return $this->hasMany('App\DnsRecord');
    }
}
