<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('accesses_id')->comment('table Accesses ID');
            $table->string('description')->nullable();
            $table->unsignedTinyInteger('status')->default(0)->comment('0-Active, 1-Inactive');
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
        Schema::dropIfExists('access_details');
    }
}
