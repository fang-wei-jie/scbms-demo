<?php

use App\Models\Operation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGranularityControlOverBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Operation::insert( array(
            [
                'attr' => 'prebook_days_ahead',
                'value' => '7',
            ],
            [
                'attr' => 'booking_cut_off_time',
                'value' => '30',
            ],
            [
                'attr' => 'precheckin_duration',
                'value' => '0',
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
        Operation::where('attr', 'prebook_days_ahead')->delete();
        Operation::where('attr', 'booking_cut_off_time')->delete();
        Operation::where('attr', 'precheckin_duration')->delete();
    }
}
