<?php

use App\Models\Operation;
use Illuminate\Database\Migrations\Migration;

class AddCourtsCountToOperationPreferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Operation::create([
            'attr' => 'courts_count',
            'value' => 9,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Operation::where('name', 'courts_count')->delete();
    }
}
