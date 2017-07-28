<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;

class PhotoController extends Controller
{
    public $onPage = 4;

    public function index()
    {
        $photos = Photo::published()->paginate($this->onPage);

        // if AJAX
        if (\Request::ajax()) {
            return \Response::json(View::make('photos.list', compact('photos'))->render());
        }
        $this->setMetaTags(null, 'Фотографии клентов', 'Фотографии клентов', '');
        return view('photos.index', compact('photos'));
    }

    public function paginate(Request $request)
    {
        $page = $request->input('page');
        $photos = Photo::published()->paginate($this->onPage);
        $html = \View::make('photos.list', compact('photos'))->render();
        $next_page = $photos->lastPage() > $photos->currentPage() ? ($photos->currentPage() + 1) : null; // номер следующей страницы
        $count = $next_page ? $photos->total() - ($photos->currentPage() * $photos->perPage()) : 0; // количество оставшихся комментариев
        return [
            'html' => $html,
            'action' => $page == 1 ? 'paginationReplace' : 'paginationAppend',
            'model' => 'photos',
            'total' => $photos->total(),
            'currentPage' => $photos->currentPage(),
            'next_page' => $next_page,
            'count' => $count,
        ];
    }
}
