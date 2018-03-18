<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScreenshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('screenshots', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url');
            $table->string('domain')->nullable();
            $table->string('title')->default('');

            $table->unsignedSmallInteger('width')->default(1280);
            $table->unsignedSmallInteger('height')->default(0);
            $table->unsignedTinyInteger('delay')->default(6);

            $table->unsignedTinyInteger('interval_value')->default(1);
            $table->unsignedInteger('interval_type')->default(86400);
            //, [60, 3600, 86400, 604800, 2592000]
            //$table->enum('interval_type', ['minutes', 'hours', 'days', 'weeks', 'months'])->default('days');

            $table->timestamp('fetched_at')->nullable();

            $table->unsignedTinyInteger('active')->default(1);
            $table->unsignedSmallInteger('shot_counter')->default(0);

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
        Schema::dropIfExists('screenshots');
    }
}
