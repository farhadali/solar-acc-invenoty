<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('_name',100);
            $table->string('_address',255)->nullable();
            $table->string('_code',255)->nullable();
            $table->date('_date')->nullable();
            $table->string('_email',60)->nullable();
            $table->string('_phone',20)->nullable();
            $table->tinyInteger('_status')->default(0);
            $table->string('_created_by',60)->nullable();
            $table->string('_updated_by',60)->nullable();
            $table->timestamps();
        });

        \DB::table('branches')->insert([
            array('_code' => 'Branch-1','_name' => 'Branch Name','_status' => 1),
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
        Schema::dropIfExists('branches');
    }
}
