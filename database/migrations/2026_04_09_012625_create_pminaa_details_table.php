<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePminaaDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pminaa_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('control_no')->nullable();
            $table->string('user_type')->nullable();
            $table->string('factory')->nullable();
            $table->string('employee_no')->nullable();
            $table->string('employee_lastname')->nullable();
            $table->string('employee_name')->nullable();
            $table->string('employee_middlename')->nullable();
            $table->string('nature_of_employment')->nullable();
            $table->string('department_agency')->nullable();
            $table->string('position_job_title')->nullable();
            $table->string('section')->nullable();
            $table->string('division')->nullable();
            $table->string('remarks')->nullable();
            $table->json('internet_access')->nullable();
            $table->json('account_system_access')->nullable();
            $table->json('network_folder_access')->nullable();
            $table->json('pc_account')->nullable();
            $table->json('email_account')->nullable();
            $table->string('requested_by')->nullable();
            $table->unsignedTinyInteger('approval_status')
                    ->default(1)
                    ->comment('
                        0-For Approval of Section Head,
                        1-For Approval of Department Head,
                        2-For Approval of ISS Manager,
                        3-For Approval of ADMIN Asst. Vice President,
                        4-For Approval of Conformance,
                        5-Approved,
                        6-Disapproved by Section Head,
                        7-Disapproved by Department Head,
                        8-Disapproved by ISS Manager,
                        9-Disapproved by ADMIN Asst. Vice President,
                        10-Disapproved by Conformance'
                    );

            $table->unsignedTinyInteger('status')->default(0)->comment('0-active, 1-Inactive');
            $table->unsignedTinyInteger('logdel')->default(0)->comment('0-Show, 1-Hide');
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
        Schema::dropIfExists('pminaa_details');
    }
}
