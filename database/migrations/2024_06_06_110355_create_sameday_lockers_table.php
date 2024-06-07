<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sameday_lockers', function (Blueprint $table) {
            $table->id();
            $table->char('city_uuid', 36)->nullable()->index();
            $table->unsignedBigInteger('locker_id');
            $table->unsignedBigInteger('county_id');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('city_id');
            $table->string('county_name', 255);
            $table->string('country_name', 255);
            $table->string('city_name', 255);
            $table->string('address', 255);
            $table->string('postal_code', 255);
            $table->string('lat', 255)->nullable();
            $table->string('lng', 255)->nullable();
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
        Schema::dropIfExists('sameday_lockers');
    }
};
