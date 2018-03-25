<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDnsRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dns_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 10);
            $table->unsignedInteger('zone_id'); 
            $table->unsignedInteger('domain_id'); 
            $table->unsignedInteger('ipaddress_id'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dns_records');
    }
}
