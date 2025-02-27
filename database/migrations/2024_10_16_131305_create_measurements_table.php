<?php

// database/migrations/create_measurements_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasurementsTable extends Migration
{
    public function up()
    {
        Schema::create('measurements', function (Blueprint $table) {
            $table->id('measurementID'); // Auto-incrementing primary key

            // Define the customerID column first
            $table->unsignedBigInteger('customerID');

            // Define the foreign key constraint after defining the column
            $table->foreign('customerID')->references('customerID')->on('customers')->onDelete('cascade');
            $table->decimal('length', 8, 2);
            $table->decimal('waist', 8, 2);
            $table->decimal('shoulder', 8, 2);
            $table->decimal('hip', 8, 2);
            $table->decimal('wrist', 8, 2);
            $table->timestamps();

            // Foreign key constraint

        });
    }

    public function down()
    {
        Schema::dropIfExists('measurements');
    }
}
