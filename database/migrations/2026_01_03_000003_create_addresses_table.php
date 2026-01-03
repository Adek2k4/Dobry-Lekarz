<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('city');
            $table->string('postal_code', 20)->nullable();
            $table->string('street')->nullable();
            $table->string('house_number')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
    }
};
