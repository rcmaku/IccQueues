<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('agent_status')->insert([
            ['name' => 'available', 'description' => 'User is available', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'unavailable', 'description' => 'User is unavailable', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Offline', 'description' => 'User is Offline', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}