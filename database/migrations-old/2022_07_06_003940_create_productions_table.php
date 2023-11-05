<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->date('_date');
            $table->string('_invoice_number')->nullable();
            $table->string('_reference')->nullable();
            $table->string('_note')->nullable();
            $table->string('_type')->nullable();
            $table->string('_p_status')->default("Pending");
            $table->integer('_from_organization_id')->default(0);
            $table->integer('_from_cost_center')->default(0);
            $table->integer('_from_branch')->default(0);
            $table->integer('_to_organization_id')->default(0);
            $table->integer('_to_cost_center')->default(0);
            $table->integer('_to_branch')->default(0);
            $table->double('_total',15,4)->default(0);
            $table->double('_stock_in__total',15,4)->default(0);
            $table->integer('_status')->default(0);
            $table->integer('_lock')->default(0);
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
        Schema::dropIfExists('productions');
    }
}
