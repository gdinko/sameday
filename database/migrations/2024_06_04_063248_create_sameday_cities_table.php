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
        Schema::create('sameday_cities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('county_id');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('city_id');
            $table->string('city_name', 255);
            $table->string('city_postal_code', 255);
            $table->string('city_latin_name', 255)->nullable();
            $table->string('county_name', 255);
            $table->string('county_code', 255);
            $table->string('county_latin_name', 255)->nullable();
            $table->string('country_name', 255);
            $table->string('country_code', 255);
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
        Schema::dropIfExists('sameday_cities');
    }
};
