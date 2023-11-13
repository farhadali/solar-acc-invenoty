<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateAccountHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_heads', function (Blueprint $table) {
            $table->id();
            $table->string('_name',100);
            $table->string('_code',100)->nullable();
            $table->integer('_account_id')->default(0);
            $table->tinyInteger('_status')->default(0);
            $table->string('_created_by',60)->nullable();
            $table->string('_updated_by',60)->nullable();
            $table->timestamps();
        });


      \DB::table('account_heads')->insert([
        array('_name' => 'Cash and cash equivalents','_account_id' => 1,'_code' => '','_status' => 1),//1
        array('_name' => 'Current assets','_account_id' => 1,'_code' => '','_status' => 1),//2
        array('_name' => 'Fixed assets','_account_id' => 1,'_code' => '','_status' => 1),//3
        array('_name' => 'Non-current assets','_account_id' => 1,'_code' => '','_status' => 1),//4
        array('_name' => 'Sundry Debtors','_account_id' => 1,'_code' => '','_status' => 1),//5
        array('_name' => 'Credit card','_account_id' => 1,'_code' => '','_status' => 1),//6
        array('_name' => 'Investments','_account_id' => 1,'_code' => '','_status' => 1),//7
        array('_name' => 'Current liabilities','_account_id' => 2,'_code' => '','_status' => 1),//8
        array('_name' => 'Non-current liabilities','_account_id' => 2,'_code' => '','_status' => 1),//9
        array('_name' => 'Accounts Payable (A/P)','_account_id' => 2,'_code' => '','_status' => 1),//10
        array('_name' => 'Loans(Liability)','_account_id' => 2,'_code' => '','_status' => 1),//11
        array('_name' => 'Suspense A/C','_account_id' => 2,'_code' => '','_status' => 1),//12
        array('_name' => 'Capital Account','_account_id' => 5,'_code' => '','_status' => 1),//13
        array('_name' => 'Direct Income','_account_id' => 3,'_code' => '','_status' => 1),//14
        array('_name' => 'Indirect income','_account_id' => 3,'_code' => '','_status' => 1),//15
        array('_name' => 'Sales Accounts','_account_id' => 3,'_code' => '','_status' => 1),//16
        array('_name' => 'Cost of sales','_account_id' => 4,'_code' => '','_status' => 1),//17
        array('_name' => 'Direct Expenses','_account_id' => 4,'_code' => '','_status' => 1),//18
        array('_name' => 'Indirect Expenses','_account_id' => 4,'_code' => '','_status' => 1),//19
        array('_name' => 'Misc. Expenses (ASSET)','_account_id' => 4,'_code' => '','_status' => 1),//20
        array('_name' => 'Purchase Accounts','_account_id' => 4,'_code' => '','_status' => 1),//21
    ]
        
    );




        
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_heads');
    }
}
