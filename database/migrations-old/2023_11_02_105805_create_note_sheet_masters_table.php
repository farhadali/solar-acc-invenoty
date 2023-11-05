<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoteSheetMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note_sheet_masters', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->integer('_branch_id')->comment('branch id = branch or division id');
            $table->integer('_cost_center_id')->comment('cost center id = project_id');

            $table->string('notesheet_no')->nullable();
            $table->string('rlp_no')->nullable();
            $table->longText('subject')->nullable();
            $table->longText('ns_info')->nullable();

            $table->integer('_user_id');
            $table->string('_user_office_id');

            $table->integer('priority');
            $table->date('request_date');
            $table->integer('request_department')->nullable();
            $table->string('request_person')->nullable();
            $table->integer('designation')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('user_remarks')->nullable();
            $table->double('totalamount')->default(0);
            $table->tinyInteger('rlp_status')->default(0);
            $table->tinyInteger('is_viewed')->default(0);
            $table->tinyInteger('is_ns')->default(0);
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
        Schema::dropIfExists('note_sheet_masters');
    }
}
