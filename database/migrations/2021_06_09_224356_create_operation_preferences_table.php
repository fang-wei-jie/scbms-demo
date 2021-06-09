<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateOperationPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_preferences', function (Blueprint $table) {
            $table->id();
            $table->string('attr');
            $table->string('value');
        });

        // Insert some stuff
        DB::table('operation_preferences') -> insert( array(
            [
                'attr' => 'name',
                'value' => 'Your Court Name'
            ],
            [
                'attr' => 'start_time',
                'value' => '10'
            ],
            [
                'attr' => 'end_time',
                'value' => '20'
            ]
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operation_preferences');
    }
}
