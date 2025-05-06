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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
        
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('timeslot_id');
            $table->unsignedBigInteger('package_id')->nullable();
            $table->integer('remaining_sessions')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled']);
            $table->timestamps();
        
            $table->foreign('session_id')->references('id')->on('sessions')->onDelete('cascade');
            $table->foreign('timeslot_id')->references('id')->on('timeslots')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('set null');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
