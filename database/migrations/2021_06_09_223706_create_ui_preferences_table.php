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
            $table->string('navbar_class');
            $table->string('navbar_text_class');
            $table->string('logo');
        });

        // Insert some stuff
        DB::table('ui_preferences') -> insert( array(
            [
                'side' => 'manager',
                'navbar_class' => 'bg-dark',
                'navbar_text_class' => 'navbar-dark',
                'logo' => 'https://icons.getbootstrap.com/assets/icons/file-person.svg'
            ],
            [
                'side' => 'admin',
                'navbar_class' => 'bg-danger',
                'navbar_text_class' => 'navbar-dark',
                'logo' => 'https://icons.getbootstrap.com/assets/icons/person-badge.svg'
            ],
            [
                'side' => '',
                'navbar_class' => 'bg-light',
                'navbar_text_class' => 'navbar-light',
                'logo' => 'https://icons.getbootstrap.com/assets/icons/hexagon-half.svg'
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
