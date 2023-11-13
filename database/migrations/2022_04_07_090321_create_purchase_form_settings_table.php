<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreatePurchaseFormSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_form_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->default(0);
            $table->integer('_default_inventory')->default(0);
            $table->integer('_default_purchase')->default(0);
            $table->integer('_default_discount')->default(0);
            $table->integer('_default_vat_account')->default(0);
            $table->integer('_opening_inventory')->default(0);
            $table->integer('_default_capital')->default(0);
            $table->integer('_show_barcode')->default(0);
            $table->integer('_show_vat')->default(0);
            $table->integer('_show_store')->default(0);
            $table->integer('_show_self')->default(0);
            $table->integer('_show_manufacture_date')->default(0);
            $table->integer('_show_expire_date')->default(0);
            $table->integer('_invoice_template')->default(0);
            $table->timestamps();
        });

        \DB::table('purchase_form_settings')->insert(
            array('_default_inventory' => 0,'_default_purchase' => 0,'_default_discount' => 0,'_default_vat_account' => 0,'_opening_inventory' => 0,'_default_capital' => 0,'_show_barcode' => 0,'_show_vat' => 0,'_show_store' => 0,'_show_self' => 0,'_show_manufacture_date' => 0,'_show_expire_date' => 0,'_invoice_template' => 1,'organization_id' => 0),
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_form_settings');
    }
}
