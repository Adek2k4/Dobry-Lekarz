<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('doctor_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->unsignedBigInteger('specialization_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('address_id')->references('id')->on('addresses')->nullOnDelete();
            $table->foreign('specialization_id')->references('id')->on('specializations')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctor_data');
    }
};
