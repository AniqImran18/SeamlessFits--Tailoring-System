<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveServiceIdFromOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['serviceID']); // Replace with the actual constraint name if needed
            // Drop the serviceID column
            $table->dropColumn('serviceID');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add the serviceID column back
            $table->foreignId('serviceID')->nullable()->constrained('services', 'serviceID')->onDelete('cascade');
        });
    }
};
