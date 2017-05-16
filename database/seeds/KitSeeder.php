<?php

use Illuminate\Database\Seeder;

use App\Models\Kit;
use App\Models\Product;

class KitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$products = Product::all();
    	$kits = factory(Kit::class, 5)->create();
    	foreach($kits as $kit) {
    		$kit->products()->sync($products->random(rand(2, 5)));
    	}
    }
}
