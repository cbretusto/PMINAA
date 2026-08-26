<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePminaaApproversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pminaa_approvers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pminaa_details_id')->comment('pminaa_details ID');
            $table->unsignedBigInteger('section_head')->comment('RapidX User ID');
            $table->date('section_head_approved_by_date')->nullable();
            $table->string('section_head_approved_by_remark')->nullable();

            $table->unsignedBigInteger('department_head')->comment('RapidX User ID');
            $table->date('department_head_approved_by_date')->nullable();
            $table->string('department_head_approved_by_remark')->nullable();

            $table->unsignedBigInteger('iss_manager')->comment('RapidX User ID');
            $table->date('iss_manager_approved_by_date')->nullable();
            $table->string('iss_manager_approved_by_remark')->nullable();

            $table->unsignedBigInteger('admin_avp')->comment('RapidX User ID');
            $table->date('admin_avp_approved_by_date')->nullable();
            $table->string('admin_avp_approved_by_remark')->nullable();
            
            $table->unsignedBigInteger('iss_hardware')->comment('RapidX User ID');
            $table->date('iss_hardware_approved_by_date')->nullable();
            $table->string('iss_hardware_approved_by_remark')->nullable();

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
        Schema::dropIfExists('pminaa_approvers');
    }
}
