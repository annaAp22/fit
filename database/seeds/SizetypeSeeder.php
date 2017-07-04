<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\CategoryProduct;
class SizetypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $this->addSizes('odezhda', 'Женский');
      $this->addSizes('odeshda', 'Мужской');
    }

    public function addSizes($categoryName, $value) {
      $attribute = Attribute::where('name', 'Тип размера')->first();
      $attributeExist = DB::table('attribute_product')->where('attribute_id', $attribute->id)->where('value', $value)->first();
      if($attributeExist) {
        return;
      }
      $mainCategory = Category::where('sysname', $categoryName)->first();
      $categories = Category::where('parent_id', $mainCategory->id)->where('deleted_at', null)->get();
      $categoryIds = array();
      foreach ($categories as $category) {
        $categoryIds[] = $category->id;
      }
      $products = CategoryProduct::whereIn('category_id', $categoryIds)->join('products', 'product_id', '=', 'products.id')->where('products.deleted_at', null)->get();
      $rows = array();
      foreach ($products as $product) {
        $rows[] = array(
            'product_id' => $product->id,
            'attribute_id' => $attribute->id,
            'value' => $value,
        );
      }
      DB::table('attribute_product')->insert($rows);
    }
}
