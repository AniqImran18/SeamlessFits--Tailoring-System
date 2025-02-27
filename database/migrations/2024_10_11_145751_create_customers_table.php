<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id('customerID'); // Use customerID as the primary key
            $table->string('name');
            $table->string('password');
            $table->string('phone_number');
            $table->string('email')->unique(); // Email should be unique
            $table->string('profile_picture')->nullable(); // Profile picture can be null
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
