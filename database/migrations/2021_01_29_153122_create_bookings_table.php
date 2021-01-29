<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('bookingID');
            $table->timestamps();
            $table->integer('custID');
            $table->integer('courtID');
            $table->integer('dateSlot');
            $table->integer('timeSlot');
            $table->integer('timeLength');
            $table->integer('rateID');
            $table->integer('bookingPrice');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
