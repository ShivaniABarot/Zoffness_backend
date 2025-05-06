<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'tutor', 'parent']);
            $table->timestamps();
        });
        
    }
    

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('remember_token');
        });
    }
}
