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
        DB::table('roles')->insert([
            ['roleName' => 'Admin', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['roleName' => 'IT Agent', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

