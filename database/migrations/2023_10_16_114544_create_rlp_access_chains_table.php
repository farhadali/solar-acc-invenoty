<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRlpAccessChainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rlp_access_chains', function (Blueprint $table) {
            $table->id();
            $table->text('chain_type');
            $table->text('chain_name')->nullable();
            $table->integer('organization_id');
            $table->integer('_branch_id')->comment('branch id = branch or division id');
            $table->integer('_cost_center_id')->comment('cost center id = project_id');
            $table->integer('department_id');
            $table->integer('rlp_type');
            $table->text('users');
            $table->integer('created_by');
            $table->integer('_user_id');
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('rlp_access_chains');
    }
}
