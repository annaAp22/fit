<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\admin\OfferRequest;
use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\Category;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', new Offer());
        $filters = $this->getFormFilter($request->input());

        $offers = Offer::orderBy('id', 'DESC');

        if (!empty($filters) && !empty($filters['id_category']))
            $offers->whereHas('categories', function ($query) use ($filters) {
                $query->where('category_id', $filters['id_category']);
            });

        if (!empty($filters) && isset($filters['status']) && $filters['status']!='') {
            $offers->where('status', $filters['status']);
        }
        if (!empty($filters) && isset($filters['deleted']) && $filters['deleted']) {
            $offers->withTrashed();
        }

        $offers = $offers->paginate($filters['perpage'], null, 'page', !empty($filters['page']) ? $filters['page'] : null);
        $categories = Category::with('children.children')->roots()->orderBy('sort')->get();

        return view('admin.offers.index', compact('offers', 'filters', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::with('children.children', 'parent')->roots()->orderBy('sort')->get();
        return view('admin.offers.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OfferRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(OfferRequest $request)
    {
        $offer = new Offer($request->all());
        $offer->uploads->upload();
        $offer->save();

        if($request->has('categories')) {
            foreach($request->input('categories') as $key => $id_category) {
                $offer->categories()->attach($id_category, ['sort' => 0]);
            }
        }

        return redirect()->route('admin.offers.index')->withMessage('Предложение добавлено.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::with('children.children')->where('parent_id', 0)->orderBy('sort')->get();
        $offer = Offer::with('categories.children')->findOrFail($id);
        return view('admin.offers.edit', compact('offer', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OfferRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(OfferRequest $request, $id)
    {
        $offer = Offer::findOrFail($id);
        $offer->uploads->upload();
        $offer->update($request->all());
        $offer->categories()->sync($request->input('categories'));
        return redirect()->route('admin.offers.index')->withMessage('Предожене изменено');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Offer::destroy($id);
        return redirect()->route('admin.offers.index')->withMessage('Предложение удалено');
    }

    /**
     * Востановление мягко удаленной
     * @param $id
     * @return mixed
     */
    public function restore($id) {
        Offer::withTrashed()->find($id)->restore();
        return redirect()->route('admin.offers.index')->withMessage('Предложение восcтановлено.');
    }
}
