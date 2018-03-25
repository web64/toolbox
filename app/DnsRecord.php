<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DnsRecord extends Model
{
    protected $guarded = [];

    public function domain()
    {
        return $this->belongsTo('App\Domain');
    }

    public function ipaddress()
    {
        return $this->belongsTo('App\Ipaddress');
    }


    public function zone()
    {
        return $this->belongsTo('App\Ipaddress');
    }
}
