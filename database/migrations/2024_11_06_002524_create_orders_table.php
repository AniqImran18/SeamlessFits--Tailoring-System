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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('orderID');
            $table->date('date');
            $table->time('time');
            $table->text('remark')->nullable();
            $table->enum('status', ['pending', 'in_process', 'completed'])->default('pending');
            // Ensure that serviceID, measurementID, customerID, and tailorID match the primary key in their respective tables
            $table->foreignId('serviceID')->constrained('services','serviceID')->onDelete('cascade');
            $table->foreignId('measurementID')->constrained('measurements','measurementID')->onDelete('cascade');
            $table->foreignId('customerID')->constrained('customers','customerID')->onDelete('cascade');
            $table->foreignId('tailorID')->nullable()->constrained('tailors','tailorID')->onDelete('cascade'); // Allow null if no tailor assigned yet
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
