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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id'); // Parent making the payment
            $table->unsignedBigInteger('package_id')->nullable(); // If payment is for a package
            $table->unsignedBigInteger('session_id')->nullable(); // If payment is for a session
            $table->string('payment_method'); // e.g., Credit Card, PayPal, UPI
            $table->decimal('amount', 10, 2); // Payment amount
            $table->string('status')->default('Pending'); // e.g., Pending, Completed, Failed
            $table->string('transaction_id')->nullable(); // Reference from payment gateway
            $table->timestamps();

            // Foreign keys
            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('set null');
            $table->foreign('session_id')->references('id')->on('sessions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
