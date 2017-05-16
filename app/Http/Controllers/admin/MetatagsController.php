<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Metatag;

class MetatagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', new Metatag());
        $filters = $this->getFormFilter($request->input());

        $metatags = Metatag::orderBy('id', 'desc');
        if (!empty($filters) && !empty($filters['url'])) {
            $metatags->where('url', 'LIKE', '%'.$filters['url'].'%');
        }
        if (!empty($filters) && !empty($filters['name'])) {
            $metatags->where('name', 'LIKE', '%'.$filters['name'].'%');
        }
        $metatags = $metatags->paginate($filters['perpage'], null, 'page', !empty($filters['page']) ? $filters['page'] : null);

        return view('admin.metatags.index', ['metatags' => $metatags, 'filters' => $filters]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //доступ на добавление
        $this->authorize('add', new Metatag());

        return view('admin.metatags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\MetatagsRequest $request)
    {
        //доступ на добавление
        $this->authorize('add', new Metatag());

        Metatag::create($request->all());
        return redirect()->route('admin.metatags.index')->withMessage('Мета-тэги добавлены');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $metatag = Metatag::find($id);
        return view('admin.metatags.edit', ['metatag' => $metatag]);
    }

    public function editRoute($route) {
        $metatag = Metatag::where('route', $route)->first();
        if(!empty($metatag)) {
            return $this->edit($metatag->id);
        } else {
            return redirect()->route('admin.metatags.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\MetatagsRequest $request, $id)
    {
        $metatag = Metatag::findOrFail($id);
        //проверка прав на изменение маршрута
        if($request->user()->can('route', $metatag)) {
            $metatag->update($request->input());
        } else {
            $metatag->update(array_except($request->input(), ['route']));
        }
        return redirect()->route('admin.metatags.index')->withMessage('Мета-тэги изменены');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //доступ на удаление
        $this->authorize('delete', new Metatag());
        Metatag::destroy($id);
        return redirect()->route('admin.metatags.index')->withMessage('Мета-тэги удалены');
    }
}
