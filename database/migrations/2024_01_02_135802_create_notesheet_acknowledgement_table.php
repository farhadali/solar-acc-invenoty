<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesheetAcknowledgementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notesheet_acknowledgement', function (Blueprint $table) {
            $table->id();
            $table->integer('ns_id');
            $table->integer('user_id');
            $table->string('user_office_id');
            $table->tinyInteger('ack_order');
            $table->tinyInteger('ack_status')->default(0);
            $table->date('ack_request_date');
            $table->date('ack_updated_date');
            $table->tinyInteger('is_visible')->default(0);
            $table->integer('_is_approve');
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
        Schema::dropIfExists('notesheet_acknowledgement');
    }
}
