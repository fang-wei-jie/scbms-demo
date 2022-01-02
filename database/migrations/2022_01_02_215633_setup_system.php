<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SetupSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone');
        });

        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('managers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('managers')->insert(['name' => 'Setup Account', 'email' => 'setup', 'password' => '$2y$10$HMwGhLO6lA7cEYthOOsT1OpbDqAMXJfEr8I5HaQKUANW28Qjsals.']);

        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('bookingID');
            $table->timestamps();
            $table->integer('custID')->nullable(true);
            $table->integer('courtID');
            $table->integer('dateSlot');
            $table->integer('timeSlot');
            $table->integer('timeLength');
            $table->bigInteger('rateRecordID');
            $table->integer('status_id');
        });

        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("status");
            $table->integer("price");
            $table->text('condition')->nullable(true);
            $table->string('dow');
            $table->timestamps();
        });

        Schema::create('rate_records', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rateID');
            $table->string('name');
            $table->integer('price');
            $table->text('condition')->nullable(true);
            $table->timestamps();
        });

        DB::table('rates')->insert(['id' => 1, 'name' => 'Weekday', 'status' => 1, 'price' => 0, 'condition' => null, 'dow' => '12345']);
        DB::table('rates')->insert(['id' => 2, 'name' => 'Weekend', 'status' => 1, 'price' => 0, 'condition' => null, 'dow' => '67']);
        DB::table('rates')->insert(['id' => 3, 'name' => 'Standard', 'status' => 0, 'price' => 0, 'condition' => null, 'dow' => '1234567']);

        DB::table('rate_records')->insert(['rateID' => 1, 'name' => 'Weekday', 'price' => 0, 'condition' => null]);
        DB::table('rate_records')->insert(['rateID' => 2, 'name' => 'Weekend', 'price' => 0, 'condition' => null]);
        DB::table('rate_records')->insert(['rateID' => 3, 'name' => 'Standard', 'price' => 0, 'condition' => null]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
        });

        Schema::dropIfExists('admins');
        Schema::dropIfExists('managers');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('rates');
        Schema::dropIfExists('rate_records');
    }
}
