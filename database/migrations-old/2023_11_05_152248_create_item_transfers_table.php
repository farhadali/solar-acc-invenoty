<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_transfers', function (Blueprint $table) {
            $table->id();
            $table->date('_date');
            $table->string('_invoice_number')->nullable();
            $table->string('_reference')->nullable();
            $table->string('_note')->nullable();
            $table->string('_type')->nullable();

            $table->string('_po_number')->nullable();
            $table->string('_rlp_no')->nullable();
            $table->string('_note_sheet_no')->nullable();
            $table->string('_workorder_no')->nullable();
            $table->string('_lc_no')->nullable();
            $table->string('_vessel_no')->nullable();
            $table->string('_arrival_date_time')->nullable();
            $table->string('_discharge_date_time')->nullable();

           

            $table->string('_p_status')->default("Pending");
            $table->integer('_from_organization_id')->default(0);
            $table->integer('_from_cost_center')->default(0);
            $table->integer('_from_branch')->default(0);
            $table->integer('_to_organization_id')->default(0);
            $table->integer('_to_cost_center')->default(0);
            $table->integer('_to_branch')->default(0);
            $table->double('_total',15,4)->default(0);

            $table->double('_total_sd_amount',15,4)->default(0);
            $table->double('_total_cd_amount',15,4)->default(0);
            $table->double('_total_ait_amount',15,4)->default(0);
            $table->double('_total_rd_amount',15,4)->default(0);
            $table->double('_total_at_amount',15,4)->default(0);
            $table->double('_total_tti_amount',15,4)->default(0);

            $table->integer('_status')->default(0);
            $table->integer('_lock')->default(0);
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
        Schema::dropIfExists('item_transfers');
    }
}
