<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRlpAccountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rlp_account_details', function (Blueprint $table) {
            $table->id();
            $table->integer('rlp_info_id');
            $table->integer('_rlp_ledger_id');
            $table->string('_rlp_ledger_description')->nullable();
            $table->text('purpose')->nullable();
            $table->double('amount')->default(0);
            $table->text('_details_remarks')->nullable();
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
        Schema::dropIfExists('rlp_account_details');
    }
}
