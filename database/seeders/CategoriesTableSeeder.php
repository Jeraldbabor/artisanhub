<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Handicrafts', 'slug' => 'handicrafts'],
            ['name' => 'Jewelry', 'slug' => 'jewelry'],
            ['name' => 'Home Decor', 'slug' => 'home-decor'],
            ['name' => 'Clothing', 'slug' => 'clothing'],
            ['name' => 'Accessories', 'slug' => 'accessories'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
