<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVesselRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vessel_routes', function (Blueprint $table) {
            $table->id();
            $table->integer('_loading_point')->nullable();
            $table->dateTime('_loading_date_time')->nullable();
            $table->integer('_unloading_point')->nullable();
            $table->dateTime('_discharge_date_time')->nullable();
            $table->dateTime('_arrival_date_time')->nullable();
            $table->integer('_purchase_no')->nullable();
            $table->integer('_final_route')->default(0);
            $table->tinyInteger('_status')->default(0);
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
        Schema::dropIfExists('vessel_routes');
    }
}
