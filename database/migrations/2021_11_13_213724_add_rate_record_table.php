<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRateRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_records', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rateID');
            $table->string('name');
            $table->integer('price');
            $table->text('condition')->nullable(true);
            $table->timestamps();
        });

        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("status");
            $table->integer("price");
            $table->text('condition')->nullable(true);
            $table->timestamps();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('bookingPrice');
            $table->dropColumn('bookingRateName');
            $table->dropColumn('condition');
            $table->bigInteger('rateRecordID');
        });

        DB::table('rates')->insert(['id' => 1, 'name' => 'Weekday', 'status' => 1, 'price' => 0, 'condition' => null]);
        DB::table('rates')->insert(['id' => 2, 'name' => 'Weekend', 'status' => 1, 'price' => 0, 'condition' => null]);
        DB::table('rates')->insert(['id' => 3, 'name' => 'Standard', 'status' => 1, 'price' => 0, 'condition' => null]);

        DB::table('rate_records')->insert(['id' => 1, 'name' => 'Weekday', 'price' => 0, 'condition' => null]);
        DB::table('rate_records')->insert(['id' => 2, 'name' => 'Weekend', 'price' => 0, 'condition' => null]);
        DB::table('rate_records')->insert(['id' => 3, 'name' => 'Standard', 'price' => 0, 'condition' => null]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rates', function (Blueprint $table) {
            $table->dropColumn('rateRecordID');
        });

        Schema::dropIfExists('rate_records');

        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('bookingPrice');
            $table->integer('bookingRateName');
            $table->text('condition')->nullable(true);
        });
    }
}
