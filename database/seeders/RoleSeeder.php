<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Role')->insert([
            'role_id' => 1,
            'key' => 'Responsable_Vente',
            'name' => 'Responsable_Vente'
        ]);
        DB::table('Role')->insert([
            'role_id' => 2,
            'key' => 'Responsable_Achat',
            'name' => 'Responsable_Achat'
        ]);
        DB::table('Role')->insert([
            'role_id' => 3,
            'key' => 'Finance_Comptabilite',
            'name' => 'Finance_Comptabilite'
        ]);
    }
}
