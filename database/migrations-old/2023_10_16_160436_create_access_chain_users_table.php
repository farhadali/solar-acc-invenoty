<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessChainUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rlp_access_chain_users', function (Blueprint $table) {
            $table->id();
            $table->integer('chain_id');
            $table->integer('user_row_id');
            $table->string('user_id');
            $table->string('user_group')->nullable()->comment('Maker,Checker,Approver');
            $table->integer('_order');
            $table->tinyInteger('_status')->default(1);
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
        Schema::dropIfExists('access_chain_users');
    }
}
