<?php

// database/migrations/xxxx_xx_xx_create_students_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('parent_name');
            $table->string('parent_phone');
            $table->string('parent_email')->unique();
            $table->string('student_name');
            $table->string('student_email')->unique()->nullable();
            $table->string('school')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
}
