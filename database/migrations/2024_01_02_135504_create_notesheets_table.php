<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notesheets', function (Blueprint $table) {
            $table->id();
            $table->string('notesheet_no');
            $table->integer('notesheet_id');
            $table->string('rlp_no');
            $table->integer('rlp_id');
            $table->integer('item');
            $table->string('item_name');
            $table->integer('unit')->nullable();
            $table->string('part_no')->nullable();
            $table->double('quantity',15,4)->default(0);
            $table->double('unit_price',15,4)->default(0);
            $table->double('total',15,4)->default(0);
            $table->longText('remarks')->nullable();
            $table->string('status');
            $table->integer('_status');
            $table->integer('_lock');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->tinyInteger('is_delete')->default(0);
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
        Schema::dropIfExists('notesheets');
    }
}
