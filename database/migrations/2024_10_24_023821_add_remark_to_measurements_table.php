<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemarkToMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('measurements', function (Blueprint $table) {
            // Adding a 'remark' column to allow tailors to write remarks for a measurement
            $table->text('remark')->nullable()->after('wrist'); // You can adjust the position using 'after'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('measurements', function (Blueprint $table) {
            // Dropping the 'remark' column on rollback
            $table->dropColumn('remark');
        });
    }
}

