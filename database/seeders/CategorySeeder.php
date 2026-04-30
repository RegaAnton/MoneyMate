<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Makanan & Minuman',
            'Transportasi',
            'Hiburan',
            'Belanja',
            'Kesehatan',
            'Pendidikan',
            'Tagihan',
            'Investasi',
            'Sosial',
            'Lain-lain',
        ];

        // Seed common categories first
        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }

        // Add more until 25 using factory
        Category::factory()->count(25 - count($categories))->create();
    }
}
