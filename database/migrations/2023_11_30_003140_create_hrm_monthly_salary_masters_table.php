<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrmMonthlySalaryMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_monthly_salary_masters', function (Blueprint $table) {
            $table->id();
            $table->integer('_employee_id');
            $table->integer('_employee_ledger_id')->default(0);
            $table->string('_emp_code')->nullable();
            $table->integer('_month');
            $table->integer('_year');
            $table->integer('_paydays')->default(0);
            $table->integer('_present_days')->default(0);
            $table->integer('_absent_days')->default(0);
            $table->integer('_arrdays')->default(0);
            $table->integer('_verify')->default(0);
            $table->double('total_earnings')->default(0);
            $table->double('total_deduction')->default(0);
            $table->double('net_total_earning')->default(0);
            $table->tinyInteger('_status')->default(0);
            $table->tinyInteger('_is_delete')->default(0);
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
        Schema::dropIfExists('hrm_monthly_salary_masters');
    }
}
