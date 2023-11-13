<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_houses', function (Blueprint $table) {
            $table->id();
            $table->string('_name');
            $table->string('_code');
            $table->integer('_branch_id');
            $table->integer('_status')->default(1);
            $table->timestamps();
        });

        \DB::table('store_houses')->insert([
            array('_code' => 'store-1','_name' => 'Store Name','_status' => 1),
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
        Schema::dropIfExists('store_houses');
    }
}
