<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Groceries', 'icon' => '🛒'],
            ['name' => 'Rent', 'icon' => '🏠'],
            ['name' => 'Utilities', 'icon' => '💡'],
            ['name' => 'Internet', 'icon' => '📡'],
            ['name' => 'Cleaning', 'icon' => '🧹'],
            ['name' => 'Entertainment', 'icon' => '🎬'],
            ['name' => 'Other', 'icon' => '📦'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
