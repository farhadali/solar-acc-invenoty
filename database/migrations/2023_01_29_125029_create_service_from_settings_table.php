<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceFromSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_from_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->default(0);
            $table->integer('_default_service_income')->default(0);
            $table->integer('_default_discount')->default(0);
            $table->integer('_default_vat_account')->default(0);
             $table->integer('_show_inline_note')->default(0);
             $table->integer('_show_service_name')->default(0);
             $table->integer('_show_vat')->default(0);
             $table->integer('_inline_discount')->default(0);
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
        Schema::dropIfExists('service_from_settings');
    }
}
