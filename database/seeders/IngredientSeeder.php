<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('ingredients')->insert([
            ['name' => 'Beef', 'stock' => 20],
            ['name' => 'Cheese', 'stock' => 5],
            ['name' => 'Onion', 'stock' => 1],
        ]);

    }
}