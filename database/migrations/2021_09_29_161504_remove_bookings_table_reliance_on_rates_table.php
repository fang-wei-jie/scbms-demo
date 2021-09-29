<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveBookingsTableRelianceOnRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('rateID', 'bookingRateName');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('bookingRateName')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('bookingRateName', 'rateID');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->bigInteger('bookingRateName')->change();
        });
    }
}
