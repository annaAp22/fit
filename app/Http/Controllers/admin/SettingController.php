<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', new Setting());
        $filters = $this->getFormFilter($request->input());

        $settings = Setting::orderBy('id', 'desc');
        if (!empty($filters) && !empty($filters['var'])) {
            $settings->where('var', 'LIKE', '%'.$filters['var'].'%');
        }
        $settings = $settings->paginate($filters['perpage'], null, 'page', !empty($filters['page']) ? $filters['page'] : null);

        return view('admin.settings.index', ['settings' => $settings, 'filters' => $filters]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //доступ на добавление
        $this->authorize('add', new Setting());

        return view('admin.settings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\admin\SettingsRequest $request)
    {
        //доступ на добавление
        $this->authorize('add', new Setting());

        $data = $request->input();
        if($request->input('type') == 'array') {
            $data['value'] = json_encode(array_combine($request->input('keys'), $request->input('values')));
        }
        Setting::create($data);
        return redirect()->route('admin.settings.index')->withMessage('Настройка добавлена');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $setting = Setting::find($id);
        $setting->getVarArray();
        return view('admin.settings.edit', ['setting' => $setting]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\admin\SettingsRequest $request, $id)
    {
        $data = $request->input();
        if($request->input('type') == 'array') {
            $data['value'] = json_encode(array_combine($request->input('keys'), $request->input('values')));
        }

        $setting = Setting::findOrFail($id);
        //проверка прав на изменение маршрута
        if($request->user()->can('var', $setting)) {
            $setting->update($data);
        } else {
            $setting->update(array_except($data, ['var']));
        }
        return redirect()->route('admin.settings.index')->withMessage('Настройка изменена');
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
        $this->authorize('delete', new Setting());
        Setting::destroy($id);
        return redirect()->route('admin.settings.index')->withMessage('Настройка удалена');
    }
}

