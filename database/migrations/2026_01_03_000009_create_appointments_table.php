<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('patient_id');
            $table->dateTime('appointment_date');
            $table->enum('status', ['scheduled','cancelled','completed'])->default('scheduled');
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('patient_id')->references('id')->on('users')->cascadeOnDelete();
            $table->index(['doctor_id','appointment_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
