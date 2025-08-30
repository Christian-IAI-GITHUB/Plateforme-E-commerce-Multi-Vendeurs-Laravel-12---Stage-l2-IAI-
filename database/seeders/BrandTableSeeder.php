<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;
use Carbon\Carbon;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['name'=>'Lacoste', 'url'=>'lacoste'],
            ['name'=>'Nike', 'url'=>'nike'],
            ['name'=>'Adidas', 'url'=>'adidas'],
            ['name'=>'Zara', 'url'=>'zara'],
            ['name'=>'H&M', 'url'=>'h-m'],
            ['name'=>'Uniqlo', 'url'=>'uniqlo'],
            ['name'=>'Levi\'s', 'url'=>'levis'],
            ['name'=>'Puma', 'url'=>'puma'],
            ['name'=>'Converse', 'url'=>'converse'],
            ['name'=>'Vans', 'url'=>'vans'],
        ];

        foreach ($brands as $data) {
            Brand::create([
                'name' => $data['name'],
                'image' => '',
                'logo' => '',
                'discount' => 0,
                'description' => '',
                'url' => $data['url'],
                'meta_title' => '',
                'meta_description' => '',
                'meta_keywords' => '',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

    }
}
