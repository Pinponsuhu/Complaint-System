<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('levels')->insert([
            'prefix' => '17',
            'level' => 'Final'
        ]);
        DB::table('levels')->insert([
            'prefix' => '18',
            'level' => 'Final'
        ]);
        DB::table('levels')->insert([
            'prefix' => '19',
            'level' => '300'
        ]);
    }
}
