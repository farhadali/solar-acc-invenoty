<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemTransferDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_transfer_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('_item_id');
            $table->foreign('_item_id')->references('id')->on('inventories');
            $table->unsignedBigInteger('_p_p_l_id');
            $table->foreign('_p_p_l_id')->references('id')->on('product_price_lists');
            $table->double('_expected_qty',15,4)->default(0);
            $table->double('_qty',15,4)->default(0);
            $table->double('_rate',15,4)->default(0);
            $table->double('_sales_rate',15,4)->default(0);
            $table->double('_discount',15,4)->default(0);
            $table->double('_discount_amount',15,4)->default(0);
            $table->double('_vat',15,4)->default(0);
            $table->double('_vat_amount',15,4)->default(0);
            $table->double('_value',15,4)->default(0);
             //For Export
            $table->double('_sd',15,4)->default(0);
            $table->double('_sd_amount',15,4)->default(0);
            $table->double('_cd',15,4)->default(0);
            $table->double('_cd_amount',15,4)->default(0);
            $table->double('_ait',15,4)->default(0);
            $table->double('_ait_amount',15,4)->default(0);
            $table->double('_rd',15,4)->default(0);
            $table->double('_rd_amount',15,4)->default(0);
            $table->double('_at',15,4)->default(0);
            $table->double('_at_amount',15,4)->default(0);
            $table->double('_tti',15,4)->default(0);
            $table->double('_tti_amount',15,4)->default(0);
            //for Export
            $table->string('_warranty')->default(0);
            $table->longText('_barcode')->nullable();
            $table->integer('_purchase_invoice_no')->nullable();
            $table->integer('_purchase_detail_id')->nullable();
            $table->date('_manufacture_date')->nullable();
            $table->date('_expire_date')->nullable();
            $table->unsignedBigInteger('_no');
            $table->foreign('_no')->references('id')->on('productions');
            $table->unsignedBigInteger('_branch_id');
            $table->foreign('_branch_id')->references('id')->on('branches');
            $table->integer('_store_id')->nullable();
            $table->integer('_cost_center_id')->nullable();
            $table->string('_store_salves_id')->nullable();
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
        Schema::dropIfExists('item_transfer_details');
    }
}
