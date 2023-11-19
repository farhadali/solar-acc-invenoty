<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrentSalaryStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('current_salary_structures', function (Blueprint $table) {
            $table->id();
            $table->integer('_master_id');
            $table->integer('_employee_id');
            $table->integer('_employee_ledger_id')->nullable();
            $table->integer('_payhead_id');
            $table->integer('_payhead_type_id')->default(1);
            $table->double('_amount')->default(0);
            $table->tinyInteger('_status')->default(1);
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
        Schema::dropIfExists('current_salary_structures');
    }
}
