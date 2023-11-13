<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVesselInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vessel_infos', function (Blueprint $table) {
            $table->id();
            $table->string('_name');
            $table->string('_code')->nullable();
            $table->string('_license_no')->nullable();
            $table->string('_country_name')->nullable();
            $table->string('_type')->nullable()->comment('Local,foreign');
            $table->string('_route')->nullable()->comment('Air,Sea,Road');
            $table->string('_owner_name')->nullable();
            $table->string('_contact_one')->nullable();
            $table->string('_contact_two')->nullable();
            $table->string('_contact_three')->nullable();
            $table->double('_capacity')->nullable();
            $table->tinyInteger('_status')->nullable();
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
        Schema::dropIfExists('vessel_infos');
    }
}
