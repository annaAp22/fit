<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LookCategory;

class LookController extends Controller
{
    public function index()
    {
        $books = LookCategory::with(['looks' => function($query) {
                        $query->published()->with('products', 'products.attributes');
                    }])
                    ->published()
                    ->get();
        if( !$books->count() ) {
            abort(404);
        }
        $this->setMetaTags(null, 'Look Book', 'Look Book', 'Look Book');

        return view('looks.index', compact('books'));
    }
}
