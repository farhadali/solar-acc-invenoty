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
            $table->string('part_no')->nullable();
            $table->timestamps();

  `` varchar(500) NOT NULL,
  `` varchar(100) NOT NULL,
  `unit` varchar(15) NOT NULL,
  `quantity` varchar(20) NOT NULL,
  `unit_price` varchar(20) NOT NULL,
  `total` varchar(20) NOT NULL,
  `remarks` varchar(500) NOT NULL,
  `status` varchar(15) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(20) NOT NULL

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
