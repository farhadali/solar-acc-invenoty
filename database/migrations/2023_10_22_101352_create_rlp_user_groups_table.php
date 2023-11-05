<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRlpUserGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rlp_user_groups', function (Blueprint $table) {
            $table->id();
            $table->string('_name');
            $table->integer('_order')->default(1);
            $table->tinyInteger('_status')->default(1);
            $table->string('_display_name')->nullable();
            $table->string('_color')->nullable();
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
        Schema::dropIfExists('rlp_user_groups');
    }
}
