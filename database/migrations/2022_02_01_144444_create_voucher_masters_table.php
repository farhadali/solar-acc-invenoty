<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoucherMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_masters', function (Blueprint $table) {
            $table->id();
            $table->string('_code')->nullable();
            $table->date('_date');
            $table->string('_time',60);
            $table->unsignedBigInteger('_user_id');
            $table->foreign('_user_id')->references('id')->on('users');
            $table->string('_user_name')->nullable();
            $table->integer('_defalut_ledger_id')->nullable();
            $table->string('_note')->nullable();
            $table->string('_voucher_type')->nullable();
            $table->string('_transection_type')->nullable();
            $table->string('_transection_ref')->nullable();
            $table->string('_form_name')->nullable();
            $table->double('_amount',15,4)->default(0);
            $table->unsignedBigInteger('_branch_id');
            $table->foreign('_branch_id')->references('id')->on('branches');
            $table->tinyInteger('_status')->default(0);
            $table->string('_created_by',60)->nullable();
            $table->string('_updated_by',60)->nullable();
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
        Schema::dropIfExists('voucher_masters');
    }
}
