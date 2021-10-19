<?php

use App\Models\UI;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReplaceNavbarColorSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ui_preferences', function (Blueprint $table) {
            $table->renameColumn('navbar_class', 'navbar_color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ui_preferences', function (Blueprint $table) {
            $table->renameColumn('navbar_color', 'navbar_class');
        });
    }
}
