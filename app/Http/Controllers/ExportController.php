<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    // Export goods to yandex market .yml
    public function yandexMarket()
    {
        // Get all categories
        $categories = Category::published()
            ->sorted()
            ->get();

        // Get all products which has ya_market == 1
        $products = Product::with(['categories' => function($query) {
            $query->select('categories.id')->take(1);
        }, 'attributes' => function($query) {
            $query->whereIn('name', ['Размеры', 'Цвет', 'Пол', 'Материал']);
        }, 'brand'])
            ->published()
            ->where('ya_market', 1)
            ->orderBy('id')
            ->get();


        $offers = collect();
        foreach($products as $product)
        {
            $nameArr = explode(' ', $product->name);
            array_pop($nameArr);
            $product->name = implode(' ', $nameArr);
            // Make offer foreach size
            if( $sizeAttr = $product->attributes->where('name', 'Размеры')->first() )
            {
                $sizes = json_decode($sizeAttr->pivot->value);
                if($sizes)
                {
                    foreach($sizes as $key => $size)
                    {
                        $n = $key < 10 ? "00" . $key : "0" . $key;
                        $offer = clone $product;
                        $offer->offer_id = $offer->id . $n;
                        $offer->size = $size;
                        $offers->push($offer);
                    }
                }
            }
            else
            {
                $product->offer_id = $product->id . "001";
                $offers->push($product);
            }
        }
        return  response()->view('export.yandex_market', compact('categories', 'offers'))->header('Content-Type', 'text/xml');
    }

    // Export goods to google merchant center .yml
    public function googleMerchant()
    {
        // Get all products which has ya_market == 1
        $products = Product::with(['categories' => function($query) {
            $query->select(['categories.id', 'categories.name']);
        }, 'attributes' => function($query) {
            $query->whereIn('name', ['Размеры', 'Цвет', 'Пол', 'Материал']);
        }, 'brand'])
            ->published()
            ->where('merchant', 1)
            ->orderBy('id')
            ->get();


        $offers = collect();
        foreach($products as $product)
        {
            // Make offer foreach size
            if( $sizeAttr = $product->attributes->where('name', 'Размеры')->first() )
            {
                $sizes = json_decode($sizeAttr->pivot->value);
                if($sizes)
                {
                    foreach($sizes as $key => $size)
                    {
                        $n = $key < 10 ? "00" . $key : "0" . $key;
                        $offer = clone $product;
                        $offer->offer_id = $offer->id . $n;
                        $offer->size = $size;
                        $offers->push($offer);
                    }
                }
            }
            else
            {
                $product->offer_id = $product->id . "001";
                $offers->push($product);
            }
        }
        return  response()->view('export.google_merchant', compact('categories', 'offers'))->header('Content-Type', 'text/xml');
    }

    // CommerceML exchange
    public function commerceMLExchange(Request $request, $token)
    {
//        ob_start();
//        var_dump($request->all());
//        $output = ob_get_clean();
//        \Storage::disk('uploads')->put('file.txt', $output);

        $password = "GS,*gw{7jB&:'HE@";
        if($token == "cead3f77f6cda6ec00f57d76c9a6879f")
        {
            $type = $request->type;
            $mode = $request->mode;

            if ($mode == 'checkauth')
            {
                $cookie = sha1($password);

                return "success\n_token\n" . $cookie;
            }
            // Catalog exchange
            if($type == 'catalog')
            {
                if($mode == 'init')
                {
                    return "zip=yes\nfile_limit=10000";
                }
                elseif($mode == 'file')
                {

                }
                elseif($mode == 'import')
                {

                }
            }
            // Orders exchange
            elseif($type == 'sale')
            {
                if($mode == 'init')
                {
                    return "zip=no\nfile_limit=10000";
                }
                // Request for orders
                elseif($mode == 'query')
                {
                    return response()->view('export.commerceML_orders')->header('Content-Type', 'text/xml');
                }
            }
        }

    }
}
