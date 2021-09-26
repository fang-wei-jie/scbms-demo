<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RevampUiPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('ui_preferences');
        Schema::create('ui_preferences', function (Blueprint $table) {
            $table->id();
            $table->string('side');
            $table->string('navbar_class');
            $table->string('navbar_text_class');
            $table->string('logo');
            $table->string('logo_invert');
        });

        DB::table('ui_preferences') -> insert( array(
            [
                'side' => 'manager',
                'navbar_class' => 'bg-dark',
                'navbar_text_class' => 'navbar-dark',
                'logo' => 'https://icons.getbootstrap.com/assets/icons/file-person.svg',
                'logo_invert' => 'invert',
            ],
            [
                'side' => 'admin',
                'navbar_class' => 'bg-danger',
                'navbar_text_class' => 'navbar-dark',
                'logo' => 'https://icons.getbootstrap.com/assets/icons/person-badge.svg',
                'logo_invert' => 'invert',
            ],
            [
                'side' => '',
                'navbar_class' => 'bg-light',
                'navbar_text_class' => 'navbar-light',
                'logo' => 'https://icons.getbootstrap.com/assets/icons/person.svg',
                'logo_invert' => 'normal',
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
        Schema::dropIfExists('ui_preferences');
    }
}
