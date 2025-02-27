<?php

// database/migrations/xxxx_xx_xx_create_appointments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('appointmentID');
            $table->unsignedBigInteger('customerID');
            $table->unsignedBigInteger('serviceID');
            $table->date('date');
            $table->time('time');
            $table->timestamps();

            $table->foreign('customerID')->references('customerID')->on('customers')->onDelete('cascade');
            $table->foreign('serviceID')->references('serviceID')->on('services')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}

