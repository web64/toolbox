<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $guarded = [];


    public function zone()
    {
        return $this->belongsTo('App\Zone');
    }

    public function records()
    {
        return $this->hasMany('App\DnsRecord');
    }

}
