<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Coffee Based',
                'description' => 'Menu olahan kopi espresso, americano, latte, dan cappuccino.',
            ],
            [
                'name' => 'Non-Coffee',
                'description' => 'Menu minuman non-kopi seperti matcha, chocolate, dan red velvet.',
            ],
            [
                'name' => 'Tea & Artisan',
                'description' => 'Teh kualitas premium dan iced tea pilihan.',
            ],
            [
                'name' => 'Signature Drink',
                'description' => 'Minuman kreasi khas dan menu andalan coffee shop.',
            ],
            [
                'name' => 'Pastry & Bakery',
                'description' => 'Roti, croissant, dan pastry segar setiap hari.',
            ],
            [
                'name' => 'Heavy Meals',
                'description' => 'Makanan berat untuk menemani ngopi.',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                ]
            );
        }
    }
}