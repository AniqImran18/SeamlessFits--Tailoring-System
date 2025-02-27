<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderServicesTable extends Migration
{
    public function up()
    {
        Schema::create('order_services', function (Blueprint $table) {
            $table->id('orderServiceID'); // Primary key
            $table->foreignId('orderID')->constrained('orders', 'orderID')->onDelete('cascade');
            $table->foreignId('serviceID')->constrained('services', 'serviceID')->onDelete('cascade');
            $table->foreignId('measurementID')->constrained('measurements', 'measurementID')->onDelete('cascade');
            $table->text('additionalRemark')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_services');
    }
};
