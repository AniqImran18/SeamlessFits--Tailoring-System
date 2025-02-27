<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTailorsTable extends Migration
{
    public function up()
    {
        Schema::create('tailors', function (Blueprint $table) {
            $table->id('tailorID'); // Use tailorID as the primary key
            $table->string('name');
            $table->string('password');
            $table->string('phone_number');
            $table->string('profile_picture')->nullable(); // Profile picture can be null
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('tailors');
    }
}
