<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'surname')) {
                $table->string('surname')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->unsignedBigInteger('role_id')->nullable()->after('id');
                $table->foreign('role_id')->references('id')->on('roles')->nullOnDelete();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            }
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('users', 'surname')) {
                $table->dropColumn('surname');
            }
        });
    }
};
