<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('patient_id');
            $table->tinyInteger('rating')->unsigned();
            $table->text('content')->nullable();
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('patient_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
