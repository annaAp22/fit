<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\LookCategory;

class LookCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', new LookCategory());
        $filters = $this->getFormFilter($request->input());

        $categories = LookCategory::sort();
        if (!empty($filters) && !empty($filters['name'])) {
            $categories->where('name', 'LIKE', '%'.$filters['name'].'%');
        }
        if (!empty($filters) && isset($filters['status']) && $filters['status']!='') {
            $categories->where('status', $filters['status']);
        }
        if (!empty($filters) && isset($filters['deleted']) && $filters['deleted']) {
            $categories->withTrashed();
        }
        $categories = $categories->paginate($filters['perpage'], null, 'page', !empty($filters['page']) ? $filters['page'] : null);

        return view('admin.look_categories.index', compact('categories', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.look_categories.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = new LookCategory($request->all());
        $category->save();
        return redirect()->route('admin.look_categories.index')->withMessage('Book added');
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
        $category = LookCategory::findOrFail($id);
        return view('admin.look_categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = LookCategory::findOrFail($id);
        $category->update($request->all());
        return redirect()->route('admin.look_categories.index')->withMessage('Book updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        LookCategory::destroy($id);
        return redirect()->route('admin.look_categories.index')->withMessage('Book deleted');
    }

    /**
     * Востановление мягко удаленной категории
     * @param $id
     * @return mixed
     */
    public function restore($id) {
        LookCategory::withTrashed()->find($id)->restore();
        return redirect()->route('admin.look_categories.index')->withMessage('Book restored');
    }

}
