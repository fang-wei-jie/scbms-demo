<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUiPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ui_preferences', function (Blueprint $table) {
            $table->id();
            $table->string('side');
            $table->string('navbar_color');
            $table->string('navbar_text_class');
            $table->string('logo');
        });

        // Insert some stuff
        DB::table('ui_preferences') -> insert( array(
            [
                'side' => 'manager',
                'navbar_color' => '343A40',
                'navbar_text_class' => 'dark',
                'logo' => 'file-person'
            ],
            [
                'side' => 'admin',
                'navbar_color' => 'DC3545',
                'navbar_text_class' => 'dark',
                'logo' => 'person-badge'
            ],
            [
                'side' => '',
                'navbar_color' => 'F8F9FA',
                'navbar_text_class' => 'light',
                'logo' => ''
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
        Schema::dropIfExists('ui_preferences');
    }
}
