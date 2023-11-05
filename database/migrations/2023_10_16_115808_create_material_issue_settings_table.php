<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialIssueSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_issue_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('_default_inventory');
            $table->integer('_default_issue_ledger');
            $table->integer('_default_sales');
            $table->integer('_default_cost_of_solds');
            $table->integer('_default_discount');
            $table->integer('_default_vat_account');
            $table->integer('_inline_discount');
            $table->integer('_show_barcode');
            $table->integer('_show_vat');
            $table->integer('_show_unit');
            $table->integer('_show_store');
            $table->integer('_show_branch');
            $table->integer('_show_cost_center');
            $table->integer('_show_self');
            $table->integer('_show_delivery_man');
            $table->integer('_show_sales_man');
            $table->integer('_show_cost_rate');
            $table->integer('_invoice_template');
            $table->integer('_show_warranty');
            $table->integer('_show_manufacture_date');
            $table->integer('_show_payment_terms');
            $table->integer('_show_expire_date');
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
        Schema::dropIfExists('material_issue_settings');
    }
}
