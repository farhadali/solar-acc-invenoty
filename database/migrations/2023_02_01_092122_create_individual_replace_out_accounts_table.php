<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndividualReplaceOutAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_replace_out_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('_no');
            $table->foreign('_no')->references('id')->on('individual_replace_masters');
            $table->unsignedBigInteger('_account_type_id');
            $table->foreign('_account_type_id')->references('id')->on('account_heads');
            $table->unsignedBigInteger('_account_group_id');
            $table->foreign('_account_group_id')->references('id')->on('account_groups');
            $table->unsignedBigInteger('_ledger_id');
            $table->foreign('_ledger_id')->references('id')->on('account_ledgers');
            $table->double('_dr_amount',15,4)->default(0);
            $table->double('_cr_amount',15,4)->default(0);
            $table->string('_type',10)->nullable();
            $table->unsignedBigInteger('_branch_id');
            $table->foreign('_branch_id')->references('id')->on('branches');
            $table->integer('organization_id')->default(0);
            $table->integer('_cost_center')->default(0);
            $table->string('_short_narr')->nullable();
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
        Schema::dropIfExists('individual_replace_out_accounts');
    }
}
