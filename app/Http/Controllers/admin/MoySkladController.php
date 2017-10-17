<?php

namespace App\Http\Controllers\admin;

use App\Library\MoySklad\Ms;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\MsCronCounter;
use App\Models\MsProduct;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
class MoySkladController extends \App\Http\Controllers\MoySkladController
{
    public function notSynchronizingProducts(Request $request)
    {
        $this->authorize('index', Product::class);

        $products = Product::with('categories.children', 'brand', 'tags');

        $filters = $request->input('filters');

        $products->select('products.*');
        $ms_type_products = MsProduct::select('product_id')->where('ms_type', 'product')->get();
        $ms_products = MsProduct::select('product_id as id')->where('size','0')->whereNotIn('product_id', $ms_type_products)->distinct('product_id')->orderBy('product_id')->get();
        $products->where('stock', 0)->whereIn('id', $ms_products->pluck('id'));
        $products   = $products->paginate(24);
        $categories = Category::with('children.children')->roots()->orderBy('sort')->get();
        $tags       = Tag::orderBy('views', 'desc')->orderBy('name')->get();
        $brands     = Brand::orderBy('name')->get();
        $attributes = Attribute::where('is_filter', 1)->orderBy('name')->get();
        $sorts = [
            'hit' => 'сначала хиты',
            'act' => 'сначала акции',
            'new' => 'сначала новинки',
            'expensive' => 'сначала дороже',
            'cheaper' => 'сначала дешевле',
        ];
        $this->setMetaTags(null, ' Список всех товаров');
        $data = [
            'title' => 'Список товаров, которые не удалось синхронизировать',
            'hide_sort' => true,
            'hide_tools' => true,
            'hide_filter' => true,
        ];
        return view('admin.products.index',compact('products', 'categories', 'tags', 'brands', 'filters', 'attributes', 'sorts', 'data'));
    }
    public function missingProducts(Request $request)
    {
        $this->authorize('index', Product::class);

        $products = Product::with('categories.children', 'brand', 'tags');

        $filters = $request->input('filters');

        $products->select('products.*')->leftJoin('ms_products', 'products.id', '=', 'ms_products.product_id')->whereNull('ms_products.id');
        $products   = $products->paginate(24);
        $categories = Category::with('children.children')->roots()->orderBy('sort')->get();
        $tags       = Tag::orderBy('views', 'desc')->orderBy('name')->get();
        $brands     = Brand::orderBy('name')->get();
        $attributes = Attribute::where('is_filter', 1)->orderBy('name')->get();
        $sorts = [
            'hit' => 'сначала хиты',
            'act' => 'сначала акции',
            'new' => 'сначала новинки',
            'expensive' => 'сначала дороже',
            'cheaper' => 'сначала дешевле',
        ];
        $this->setMetaTags(null, ' Список всех товаров');
        $data = [
            'title' => 'Список товаров, которые не найдены на складе',
            'hide_sort' => true,
            'hide_tools' => true,
            'hide_filter' => true,
        ];
        return view('admin.products.index',compact('products', 'categories', 'tags', 'brands', 'filters', 'attributes', 'sorts', 'data'));
    }
    //
    public function updateProducts() {
        $message = $this->importProducts(new Ms());
        if(!$message) {
            $message = 'Сервер не отвечает, попробуйте повторить через несколько минут';
        }else {
            $message = str_replace('Products synced count', 'Синхронизировано товаров', $message);
        }
        return redirect()->route('admin.moysklad.sync')->with('message', $message);
    }
    public function sync() {
        $cron_counter = MsCronCounter::all()->keyBy('action');
        return view('admin.moysklad.sync', compact('cron_counter'));
    }
    public function updateAttributes() {
        $message = $this->updatePriceAndStock(new Ms());
        if(!$message) {
            $message = 'Сервер не отвечает, попробуйте повторить через несколько минут';
        }else {

        }
        return redirect()->route('admin.moysklad.sync')->with('message', $message);
    }
}
