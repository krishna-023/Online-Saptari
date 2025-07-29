<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Business;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        Business::create([
            'title' => 'ABC Restaurant',
            'description' => 'A great place for food lovers.',
            'image' => 'images/abc_restaurant.jpg',
            'contact' => '123-456-7890',
            'opening_hours' => '9 AM - 10 PM'
        ]);

        Business::create([
            'title' => 'XYZ Store',
            'description' => 'Best place for shopping.',
            'image' => 'images/xyz_store.jpg',
            'contact' => '987-654-3210',
            'opening_hours' => '8 AM - 9 PM'
        ]);
    }
}
