<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveMeasurementIdFromOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the foreign key constraint on measurementID
            $table->dropForeign(['measurementID']); // Adjust the constraint name if needed
            // Drop the measurementID column
            $table->dropColumn('measurementID');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Re-add the measurementID column
            $table->foreignId('measurementID')->nullable()->constrained('measurements', 'measurementID')->onDelete('cascade');
        });
    }
};
