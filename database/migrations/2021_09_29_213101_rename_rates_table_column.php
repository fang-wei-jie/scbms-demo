<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameRatesTableColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rates', function (Blueprint $table) {
            $table->renameColumn('rateName', 'name');
            $table->renameColumn('rateStatus', 'status');
            $table->renameColumn('ratePrice', 'price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rates', function (Blueprint $table) {
            $table->renameColumn('name', 'rateName');
            $table->renameColumn('status', 'rateStatus');
            $table->renameColumn('price', 'ratePrice');
        });
    }
}
