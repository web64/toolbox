<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Screenshot extends Model
{
    public function newImagePath()
    {
        $domain = parse_url($this->url, PHP_URL_HOST);
        $timestamp = date('Ymd-Hi');

        return "screenshots/{$this->id}-{$domain}/{$timestamp}.jpg";
    }
}
