<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->string('_order_number')->nullable();
            $table->date('_date');
            $table->string('_time',60);
            $table->integer('_order_ref_id')->default(0);
            
            $table->string('_purchase_type')->nullable()->comment('Local,Import');
            $table->string('_po_number')->nullable();
            $table->string('_rlp_no')->nullable();
            $table->string('_note_sheet_no')->nullable();
            $table->string('_workorder_no')->nullable();
            $table->string('_lc_no')->nullable();
            $table->string('_vessel_no')->nullable();
            $table->string('_arrival_date_time')->nullable();
            $table->string('_discharge_date_time')->nullable();

            $table->double('_total_sd_amount',15,4)->default(0);
            $table->double('_total_cd_amount',15,4)->default(0);
            $table->double('_total_ait_amount',15,4)->default(0);
            $table->double('_total_rd_amount',15,4)->default(0);
            $table->double('_total_at_amount',15,4)->default(0);
            $table->double('_total_tti_amount',15,4)->default(0);


            $table->string('_referance')->nullable();
            $table->unsignedBigInteger('_ledger_id');
            $table->foreign('_ledger_id')->references('id')->on('account_ledgers');
            $table->unsignedBigInteger('_user_id');
            $table->foreign('_user_id')->references('id')->on('users');
            $table->string('_user_name')->nullable();
            $table->string('_note')->nullable();
            $table->double('_sub_total',15,4)->default(0);
            $table->double('_discount_input',15,4)->default(0);
            $table->double('_total_discount',15,4)->default(0);
            $table->double('_total_vat',15,4)->default(0);
            $table->double('_total',15,4)->default(0);
            $table->double('_p_balance',15,4)->default(0);
            $table->double('_l_balance',15,4)->default(0);
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
        Schema::dropIfExists('purchase_returns');
    }
}
