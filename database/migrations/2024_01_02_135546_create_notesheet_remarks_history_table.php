<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesheetRemarksHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notesheet_remarks_history', function (Blueprint $table) {
            $table->id();
            $table->integer('notesheet_id');
            $table->integer('user_id');
            $table->string('user_office_id')->nullable();
            $table->text('remarks')->nullable();
            $table->date('remarks_date')->nullable();
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
        Schema::dropIfExists('notesheet_remarks_history');
    }
}
