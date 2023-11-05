<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndividualReplaceFormSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_replace_form_settings', function (Blueprint $table) {
             $table->id();
             $table->integer('organization_id')->default(0);
            $table->integer('_default_inventory')->default(0);
            $table->integer('_default_sales')->default(0);
            $table->integer('_default_cost_of_solds')->default(0);
            $table->integer('_default_discount')->default(0);
            $table->integer('_default_vat_account')->default(0);
            $table->integer('_inline_discount')->default(0);
            $table->integer('_show_barcode')->default(0);
            $table->integer('_show_vat')->default(0);
            $table->integer('_show_store')->default(0);
            $table->integer('_show_self')->default(0);
            $table->integer('_show_delivery_man')->default(0);
            $table->integer('_show_sales_man')->default(0);
            $table->integer('_show_cost_rate')->default(0);
            $table->integer('_invoice_template')->default(0);
            $table->integer('_show_warranty')->default(0);
            $table->integer('_show_manufacture_date')->default(0);
            $table->integer('_show_expire_date')->default(0);
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
        Schema::dropIfExists('individual_replace_form_settings');
    }
}
