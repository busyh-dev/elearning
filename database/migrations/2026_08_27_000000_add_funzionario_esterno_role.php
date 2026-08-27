<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddFunzionarioEsternoRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $exists = DB::table('roles')->where('name', 'Funzionario Esterno')->exists();
        if (!$exists) {
            DB::table('roles')->insert([
                'name' => 'Funzionario Esterno',
                'type' => 'User Defined',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('roles')->where('name', 'Funzionario Esterno')->delete();
    }
}
