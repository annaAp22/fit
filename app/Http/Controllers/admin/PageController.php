<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\PagePhoto;
use App\Models\Page;

use Validator;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', new Page());
        $filters = $this->getFormFilter($request->input());

        $pages = Page::orderBy('id', 'desc');
        if (!empty($filters) && !empty($filters['sysname'])) {
            $pages->where('sysname', 'LIKE', '%'.$filters['sysname'].'%');
        }
        if (!empty($filters) && !empty($filters['name'])) {
            $pages->where('name', 'LIKE', '%'.$filters['name'].'%');
        }
        $pages = $pages->paginate($filters['perpage'], null, 'page', !empty($filters['page']) ? $filters['page'] : null);

        return view('admin.pages.index', ['pages' => $pages, 'filters' => $filters]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //доступ на добавление
        $this->authorize('add', new Page());

        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\admin\PageRequest $request)
    {
        //доступ на добавление
        $this->authorize('add', new Page());

        $page = Page::create($request->all());
        if($request->has('vars')) {
            foreach($request->input('vars') as $key => $var) {
                if(!empty($var)) {
                    $value = $request->input('values')[$key];

                    $validator = Validator::make(['var' => $var, 'value' => $value], \App\Models\PageVar::$rules);
                    if ($validator->fails())
                        return back()->withErrors($validator)->withInput();

                    $value = $request->input('values')[$key];
                    $page->vars()->create(['var' => $var, 'value' => $value]);
                }
            }
        }

        if($request->file('photos')) {
            foreach($request->file('photos') as $key => $photo) {
                $validator = Validator::make(['img' => $photo], PagePhoto::$rules);
                if ($validator->passes()) {
                    $name = $request->input('names')[$key] ?: '';
                    $photo = new PagePhoto(['name' => $name, 'page_id' => $page->id]);
                    $photo->uploads->upload(['img' => 'photos.'.$key]);
                    $photo->save();
                }
            }
        }

        return redirect()->route('admin.pages.index')->withMessage('Страница добавлена');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page = Page::find($id);
        return view('admin.pages.edit', ['page' => $page]);
    }

    public function editSysname($sysname) {
        $page = Page::where('sysname', $sysname)->firstOrFail();
        return $this->edit($page->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\admin\PageRequest $request, $id)
    {
        $page = Page::find($id);
        //проверка прав на изменение системного имени
        if($request->user()->can('sysname', $page)) {
            $page->update($request->input());
        } else {
            $page->update(array_except($request->input(), ['sysname']));
        }

        if($request->has('var_ids'))
            $page->vars()->whereNotIn('id', $request->input('var_ids'))->delete();

        if($request->has('vars')) {
            foreach($request->input('vars') as $key => $var) {
                if(!empty($var)) {
                    $value = $request->input('values')[$key];

                    $validator = Validator::make(['var' => $var, 'value' => $value], \App\Models\PageVar::$rules);
                    if ($validator->fails()) {
                        return back()->withErrors($validator)->withInput();
                    }

                    if($request->input('var_ids')[$key]) {
                        $page->vars()->find($request->input('var_ids')[$key])->update(['var' => $var, 'value' => $value]);
                    } else {
                        $page->vars()->create(['var' => $var, 'value' => $value]);
                    }
                }
            }
        }
        
        $data = $request->all();
        //change and delete photos
        if($request->has('p_ids')) {
            foreach($data['p_ids'] as $key => $id_photo) {
                $photo = $page->photos()->find($id_photo);
                if(!empty($photo)) {
                    //delete
                    if (!empty($data['p_delete'][$key])) {
                        $photo->uploads->delete();
                        $photo->delete();
                        //change
                    } else {
                        $photo->update(['name' => $data['p_names'][$key]]);
                    }
                }
            }
        }

        if($request->file('photos')) {
            foreach($request->file('photos') as $key => $photo) {
                $validator = Validator::make(['img' => $photo], PagePhoto::$rules);
                if ($validator->passes()) {
                    $name = $request->input('names')[$key] ?: '';
                    $photo = new PagePhoto(['name' => $name, 'page_id' => $id]);

                    $photo->uploads->upload([ 'img' => 'photos.'.$key ]);
                    $photo->save();
                }
            }
        }


        return redirect()->route('admin.pages.index')->withMessage('Страница изменена');
    }


    public function updateContent(Request $request, $sysname)
    {
        $page = Page::where('sysname', $sysname)->firstOrFail();
        $page->update(['content' => $request->input('content')]);
        return redirect()->back()->withMessage('Содержание изменено');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = Page::find($id);
        //доступ на удаление
        $this->authorize('delete', $page);

        foreach($page->photos as $photo) {
            $photo->uploads->delete();
            $photo->delete();
        }
        $page->delete();

        return redirect()->route('admin.pages.index')->withMessage('Страница удалена');
    }
}
