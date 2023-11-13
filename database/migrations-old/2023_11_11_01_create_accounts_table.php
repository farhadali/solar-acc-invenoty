<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('_ref_master_id')->default(0);
            $table->integer('_ref_detail_id')->default(0);
            $table->string('_short_narration')->nullable();
            $table->string('_narration')->nullable();
            $table->string('_reference')->nullable();
            $table->string('_transaction')->nullable();
            $table->string('_voucher_type')->default('JV');
            $table->date('_date')->nullable();
            $table->string('_table_name')->nullable();
            $table->integer('_account_head')->default(0);
            $table->integer('_account_group')->default(0);
            $table->integer('_account_ledger')->default(0);
            $table->double('_dr_amount',15,4)->default(0);
            $table->double('_cr_amount',15,4)->default(0);
            $table->integer('organization_id')->default(1);
            $table->integer('_branch_id')->default(1);
            $table->integer('_cost_center')->default(1);
            $table->string('_name')->nullable();
            $table->integer('_status')->default(1);
            $table->integer('_serial')->default(1);
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
        Schema::dropIfExists('accounts');
    }
}
