<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateFeaturesPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('features_preferences', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('value');
            $table->timestamps();
        });

        // Insert some stuff
        DB::table('features_preferences') -> insert( array(
            [
                'name' => 'delete_booking',
                'value' => '0',
            ],
            [
                'name' => 'customer_delete',
                'value' => '0',
            ],
            [
                'name' => 'admin_delete',
                'value' => '0',
            ],
            [
                'name' => 'admin_role',
                'value' => '1',
            ],
            [
                'name' => 'admin_sales_report',
                'value' => '0',
            ],
            [
                'name' => 'rates',
                'value' => '1',
            ],
            [
                'name' => 'rates_weekend_weekday',
                'value' => '1',
            ],
            [
                'name' => 'rates_editable_admin',
                'value' => '1',
            ],
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('features_preferences');
    }
}
