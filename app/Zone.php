<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $guarded = [];

    public function domains()
    {
        return $this->hasMany('App\Domain');
    }


    public function records()
    {
        return $this->hasMany('App\DnsRecord');
    }
}
