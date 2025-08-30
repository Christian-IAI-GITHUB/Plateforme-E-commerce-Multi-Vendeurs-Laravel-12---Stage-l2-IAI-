<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Carbon\Carbon;

class CategoryTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['parent_id' => null, 'name' => 'Vêtements', 'url' => 'vetements'],
            ['parent_id' => null, 'name' => 'Électronique', 'url' => 'electronique'],
            ['parent_id' => null, 'name' => 'Électroménager', 'url' => 'electromenager'],
            ['parent_id' => 1, 'name' => 'Hommes', 'url' => 'hommes'],
            ['parent_id' => 1, 'name' => 'Femmes', 'url' => 'femmes'],
            ['parent_id' => 1, 'name' => 'Enfants', 'url' => 'enfants'],
            ['parent_id' => 4, 'name' => 'T-Shirts Hommes', 'url' => 't-shirts-hommes'],
        ];
        
        foreach ($categories as $data) {
                Category::create([
                    'parent_id' => $data['parent_id'],
                    'name' => $data['name'],
                    'url' => $data['url'],
                    'image' => '',
                    'size_chart' => '',
                    'discount' => 0,
                    'description' => '',
                    'meta_title' => '',
                    'meta_description' => '',
                    'meta_keywords' => '',
                    'status' => 1,
                    'menu_status' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
}
