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
            ['name' => 'Beef', 'stock_Kg' => 20],
            ['name' => 'Cheese', 'stock_Kg' => 5],
            ['name' => 'Onion', 'stock_Kg' => 1],
        ]);

    }
}