<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRlpDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rlp_details', function (Blueprint $table) {
            $table->id();
            $table->integer('rlp_info_id');
            $table->integer('_item_id');
            $table->string('_item_description')->nullable();
            $table->text('purpose')->nullable();
            $table->double('quantity')->default(0);
            $table->integer('_unit_id');
            $table->double('unit_price')->default(0);
            $table->double('amount')->default(0);
            $table->integer('_ledger_id')->nullable()->comment('supplier');
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
        Schema::dropIfExists('rlp_details');
    }
}
