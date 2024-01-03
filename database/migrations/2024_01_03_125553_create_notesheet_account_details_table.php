<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesheetAccountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notesheet_account_details', function (Blueprint $table) {
            $table->id();
            $table->integer('ns_info_id');
            $table->integer('_ns_ledger_id');
            $table->string('_ns_ledger_description')->nullable();
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
        Schema::dropIfExists('notesheet_account_details');
    }
}
