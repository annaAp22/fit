<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Product;
use App\Models\ProductComment;

use Carbon\Carbon;

class ProductCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', new ProductComment());
        $filters = $this->getFormFilter($request->input());

        $comments = ProductComment::orderBy('id', 'desc');
        if (!empty($filters) && !empty($filters['product_id'])) {
            $comments->where('product_id', $filters['product_id']);
            $filters['product'] = Product::where('id', $filters['product_id'])->first();
        }
        if (!empty($filters) && !empty($filters['date_from'])) {
            $comments->where('date', '>=', (new Carbon($filters['date_from']))->format('Y.m.d'));
        }
        if (!empty($filters) && !empty($filters['date_to'])) {
            $comments->where('date', '<=', (new Carbon($filters['date_to']))->format('Y.m.d'));
        }
        if (!empty($filters) && isset($filters['status']) && $filters['status']!='') {
            $comments->where('status', $filters['status']);
        }
        $comments = $comments->paginate($filters['perpage'], null, 'page', !empty($filters['page']) ? $filters['page'] : null);

        return view('admin.comments.index', ['comments' => $comments, 'filters' => $filters]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = [];
        if(old() && old('product_id')) {
            $product = Product::where('id', old('product_id'))->first();
        }
        return view('admin.comments.create', ['product' => $product]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\admin\ProductCommentRequest $request)
    {
        $data = $request->all();
        $data['date'] = (new Carbon($data['date']))->format('Y.m.d');
        ProductComment::create($data);

        return redirect()->route('admin.comments.index')->withMessage('Комментарий добавлен');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $comment = ProductComment::findOrFail($id);
        if(old() && old('product_id')) {
            $product = Product::where('id', old('product_id'))->first();
        } elseif($comment->product_id) {
            $product = Product::where('id', $comment->product_id)->first();
        }
        return view('admin.comments.edit', ['comment' => $comment,'product' => (!empty($product) ? $product : [])]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\admin\ProductCommentRequest $request, $id)
    {
        $comment = ProductComment::findOrFail($id);
        $data = $request->all();
        $data['created_at'] = strtotime($data['date']);
        $comment->update($data);
        return redirect()->route('admin.comments.index')->withMessage('Комментарий изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ProductComment::destroy($id);
        return redirect()->route('admin.comments.index')->withMessage('Комментарий удален');
    }

}
