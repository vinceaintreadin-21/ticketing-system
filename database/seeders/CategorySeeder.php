<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = [
            'Hardware',
            'Software',
            'Network',
            'Others'
        ];

        foreach ($category as $name) {
            Category::create(['category_name' => $name]);
        }

    }
}
