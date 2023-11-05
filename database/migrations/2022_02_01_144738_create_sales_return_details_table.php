<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_return_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('_item_id');
            $table->foreign('_item_id')->references('id')->on('inventories');
            $table->unsignedBigInteger('_p_p_l_id');
            $table->foreign('_p_p_l_id')->references('id')->on('product_price_lists');
            $table->integer('_sales_ref_id')->nullable();
            $table->integer('_sales_detail_ref_id')->nullable();
            $table->double('_qty',15,4)->default(0);
            $table->double('_rate',15,4)->default(0);
            $table->double('_sales_rate',15,4)->default(0);
            $table->double('_discount',15,4)->default(0);
            $table->double('_vat',15,4)->default(0);
            $table->double('_value',15,4)->default(0);
            $table->double('_expected_qty',15,4)->default(0);
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
            
            $table->integer('_store_id')->default(0);
            $table->string('_warranty')->default(0);
            $table->integer('_cost_center_id')->default(0);
            $table->integer('_store_salves_id')->default(0);
            $table->date('_manufacture_date')->nullable();
            $table->date('_expire_date')->nullable();
            $table->unsignedBigInteger('_no');
            $table->foreign('_no')->references('id')->on('sales_returns');
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
        Schema::dropIfExists('sales_return_details');
    }
}
