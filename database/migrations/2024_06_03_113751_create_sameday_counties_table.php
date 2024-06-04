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
        Schema::create('sameday_counties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('county_id');
            $table->unsignedBigInteger('country_id');
            $table->string('country', 255);
            $table->string('name', 255);
            $table->string('code', 255);
            $table->string('latin_name', 255);
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
        Schema::dropIfExists('sameday_counties');
    }
};
