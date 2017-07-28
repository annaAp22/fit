<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Photo;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', new Photo());
        $filters = $this->getFormFilter($request->input());

        $photos = Photo::orderBy('id', 'desc');


        if (!empty($filters) && isset($filters['status']) && $filters['status']!='') {
            $photos->where('status', $filters['status']);
        }
        if (!empty($filters) && isset($filters['deleted']) && $filters['deleted']) {
            $photos->withTrashed();
        }

        $photos = $photos->paginate($filters['perpage'], null, 'page', !empty($filters['page']) ? $filters['page'] : null);

        return view('admin.photos.index', ['photos' => $photos,'filters' => $filters]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.photos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\admin\PhotoRequest $request)
    {
        $photo = new Photo($request->all());
        $photo->uploads->upload();
        $photo->save();

        return redirect()->route('admin.photos.index')->withMessage('Фото добавлено.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $photo = Photo::findOrFail($id);
        return view('admin.photos.edit', ['photo' => $photo]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\admin\PhotoRequest $request, $id)
    {
        $photo = Photo::findOrFail($id);
        $photo->uploads->upload();
        $photo->update($request->all());
        return redirect()->route('admin.photos.index')->withMessage('Фото изменено.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $photo = Photo::find($id);
        $photo->uploads->delete();
        Photo::destroy($id);
        return redirect()->route('admin.photos.index')->withMessage('Фото удалено.');
    }

}
