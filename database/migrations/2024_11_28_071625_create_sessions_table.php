<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('session_type', ['study', 'exam', 'extended_exam','regular']);
            $table->decimal('price_per_slot', 8, 2);
            $table->unsignedBigInteger('tutor_id');
            $table->integer('max_capacity');
            $table->timestamps();
        
            $table->foreign('tutor_id')->references('id')->on('tutors')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions');
    }
};
