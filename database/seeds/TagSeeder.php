<?php

use Illuminate\Database\Seeder;

use App\Models\Tag;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::all();
        $brands = Brand::all();
        factory(Tag::class, 10)
            ->create()
            ->each(function($t) use ($categories, $brands) {
                $products = factory(Product::class, 3)
                    ->make([
                        'category_id' => $categories->random(),
                        'brand_id' => $brands->random(),
                    ]);
                foreach($products as $p)
                    $t->products()->attach($p->id, ['sort' => rand(0, 1000)]);
            });
    }
}
