<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_status', function (Blueprint $table) {
            $table->id();
            $table->string('_name');
            $table->timestamps();
        });

         \DB::table('production_status')->insert([
            array('_name' => 'Transit'),
            array('_name' => 'Work-in-progress'),
            array('_name' => 'Complete'),
        ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_statuses');
    }
}
