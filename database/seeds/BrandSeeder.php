<?php

use Illuminate\Database\Seeder;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::all();
        factory(Brand::class, 10)
            ->create()
            ->each(function($b) use ($categories) {
                $category = $categories->random();
                $products = factory(Product::class, 3)
                    ->make();
                $b->products()->saveMany($products);
                $category->products()->attach($products, ['sort' => 0]);
            });
    }
}
