<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrmPayHeadTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_pay_head_types', function (Blueprint $table) {
            $table->id();
            $table->string('_name');
            $table->tinyInteger('cal_type')->default(1)->comment('1=Add,2=Deduction');
            $table->integer('_status')->default(0);
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
        Schema::dropIfExists('hrm_pay_head_types');
    }
}
