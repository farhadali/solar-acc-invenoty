<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('_code');
            $table->string('_name');
            $table->longText('_details')->nullable();
            $table->longText('_address')->nullable();
            $table->string('_bin')->nullable();
            $table->integer('_status');
            $table->integer('_user');
            $table->timestamps();
        });

        \DB::table('companies')->insert([
            array('_code' => 'code-1','_name' => 'company Name','_status' => 1),
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
        Schema::dropIfExists('companies');
    }
}
