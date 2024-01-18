<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkordersMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workorders_master', function (Blueprint $table) {
            $table->id();
            $table->date('_date');
            $table->integer('organization_id');
            $table->string('notesheet_prefix');
            $table->integer('_branch_id');
            $table->integer('_cost_center_id');
            $table->integer('request_project');
            $table->integer('request_warehouse');
            $table->integer('_store_id');
            $table->string('wo_no')->nullable();
            $table->integer('rlp_id');
            $table->string('rlp_no')->nullable();
            $table->integer('notesheet_id')->nullable();
            $table->string('notesheet_no')->nullable();
            $table->text('subject')->nullable();
            $table->longText('wo_info')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('address')->nullable();
            $table->string('concern_person')->nullable();
            $table->string('cell_number')->nullable();
            $table->string('email')->nullable();
            $table->double('no_of_item')->default(0);
            $table->double('sub_total')->default(0);
            $table->double('ait_input')->default(0);
            $table->double('total_ait')->default(0);
            $table->double('vat_input')->default(0);
            $table->double('total_vat')->default(0);
            $table->double('_discount_input')->default(0);
            $table->double('total_discount')->default(0);
            $table->double('total_afterdiscount')->default(0);
            $table->double('grand_total')->default(0);
            $table->longText('remarks')->nullable();
            $table->longText('terms_condition')->nullable();
            $table->tinyInteger('notesheet_status')->default(0);
            $table->string('status')->default(0);
            $table->tinyInteger('is_wo')->default(0);
            $table->string('attached_file')->nullable();
            $table->tinyInteger('is_viewed')->default(0);
            $table->tinyInteger('is_ns')->default(0);
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
        Schema::dropIfExists('workorders_master');
    }
}
