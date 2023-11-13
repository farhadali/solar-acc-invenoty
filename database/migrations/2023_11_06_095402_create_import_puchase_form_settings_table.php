<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportPuchaseFormSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_puchase_form_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->default(0);
            $table->integer('_user_id')->default(0);
            $table->integer('_default_inventory')->default(0);
            $table->integer('_default_purchase')->default(0);
            $table->integer('_default_discount')->default(0);
            $table->integer('_default_vat_account')->default(0);
            $table->integer('_default_sd_account')->default(0);
            $table->integer('_default_cd_account')->default(0);
            $table->integer('_default_ait_account')->default(0);
            $table->integer('_default_rd_account')->default(0);
            $table->integer('_default_tti_account')->default(0);
            $table->integer('_opening_inventory')->default(0);
            $table->integer('_default_capital')->default(0);
            $table->integer('_show_barcode')->default(0);
            $table->integer('_show_vat')->default(0);
            $table->integer('_show_store')->default(0);
            $table->integer('_show_self')->default(0);
            $table->integer('_show_sd')->default(0);
            $table->integer('_show_cd')->default(0);
            $table->integer('_show_ait')->default(0);
            $table->integer('_show')->default(0);
            $table->integer('_show_rd')->default(0);
            $table->integer('_show_at')->default(0);
            $table->integer('_show_tti')->default(0);
            $table->integer('_show_manufacture_date')->default(0);
            $table->integer('_show_po')->default(0);
            $table->integer('_show_rlp')->default(0);
            $table->integer('_show_note_sheet')->default(0);
            $table->integer('_show_wo')->default(0);
            $table->integer('_show_lc')->default(0);
            $table->integer('_show_vn')->default(0);

            $table->integer('_show_expire_date')->default(0);
            $table->integer('_invoice_template')->default(0);
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
        Schema::dropIfExists('import_puchase_form_settings');
    }
}
