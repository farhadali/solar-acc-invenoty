<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\MainAccountHead;

class CreateMainAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_account_head', function (Blueprint $table) {
            $table->id();
            $table->string('_name');
            $table->timestamps();
        });


        \DB::table('main_account_head')->insert([
            ['_name' => 'Assets'],
            ['_name' => 'Liability'],
            ['_name' => 'Income'],
            ['_name' => 'Expenses'],
            ['_name' => 'Capital'],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('main_account_head');
    }
}
