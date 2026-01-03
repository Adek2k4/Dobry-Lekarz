<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('ticket_type_id')->nullable();
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('patient_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('ticket_type_id')->references('id')->on('ticket_types')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
