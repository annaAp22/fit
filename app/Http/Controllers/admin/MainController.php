<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Cache;
use phpbb\viewonline_helper;
use Validator;
use Image;

class MainController extends Controller
{
    /**
     * Главная админки
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        return view('admin.main.index');
    }

    /**
     * Страница создания превью картинок
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function crop(Request $request) {
        return view('admin.main.crop', ['input' => $request->input()]);
    }

    /**
     * Сохранение превью
     * @param Request $request
     */
    public function cropUpdate(Request $request) {
        $validator = Validator::make($request->all(), [
            'img' => 'required',
            'preview' => 'required',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'x2' => 'required|numeric|min:'.$request->input('width'),
            'y2' => 'required|numeric|min:'.$request->input('height'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withMessage('Необходимо выбрать область для создания превью');
        }
        $img = Image::make(public_path($request->input('img')));

        $width_area = $request->input('x2') - $request->input('x1');
        $height_area = $request->input('y2') - $request->input('y1');

        $img->crop($width_area, $height_area, $request->input('x1'), $request->input('y1'));
        $img->fit($request->input('width'), $request->input('height'), function ($constraint) {
            $constraint->upsize();
        });
        $img->save(public_path($request->input('preview')));

        if($request->has('previews')) {
            foreach ($request->input('previews') as $key => $preview) {
                $img = Image::make(public_path($request->input('img')));
                $img->crop($width_area, $height_area, $request->input('x1'), $request->input('y1'));
                $img->fit($request->input('widths')[$key], $request->input('heights')[$key], function ($constraint) {
                    $constraint->upsize();
                });
                $img->save(public_path($preview));
            }
        }
        return redirect()->back()
            ->withInput()
            ->withMessage('Изображение сохранено');
    }

    /**
     * Сохранение на сервере изображений загруженных через CKeditor
     * @param Request $request
     */
    public function uploadFileCKeditor(Request $request) {
        $full_path = '';
        if(!$request->file('upload')->isValid()) {
            $message = 'При загрузке файла произошла ошибка';
        } else {
            $validator = Validator::make($request->all(), [
                'upload' => 'required|mimes:jpeg,bmp,png,gif,svg,doc,pdf|max:2000'
            ]);
            if ($validator->fails()) {
                $message = implode($validator->messages()->all(), "<br>");
            } else {
                $path = '/assets/uploads/';
                $filename = time().$request->file('upload')->getClientOriginalName();
                $request->file('upload')->move(public_path($path), $filename);

                $full_path = $path.$filename;
                $message = 'Файл '.$filename.' Загружен';
            }
        }
        $callback = $request->input('CKEditorFuncNum');

        echo '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction("'.$callback.'", "'.$full_path.'", "'.$message.'" );</script>';
        return;
    }
    /*
     * чистим кэш
     * **/
    public function cacheClear() {
        Cache::flush();
        return view('admin.cache');
    }
}
