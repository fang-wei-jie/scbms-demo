<?php

use App\Models\Operation;
use Illuminate\Database\Migrations\Migration;

class AddPhoneAddressToOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Operation::insert(['attr' => 'phone', 'value' => '0123456789']);
        Operation::insert(['attr' => 'address', 'value' => 'Address']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Operation::where('attr', 'phone')->delete();
        Operation::where('attr', 'address')->delete();
    }
}
