<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\UserGroup;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', new User());
        $filters = $this->getFormFilter($request->input());

        $users = User::orderBy('id', 'desc');
        if (!empty($filters) && !empty($filters['group_id'])) {
            $users->where('group_id', $filters['group_id']);
        }
        if (!empty($filters) && !empty($filters['email'])) {
            $users->where('email', 'LIKE', '%'.$filters['email'].'%');
        }
        if (!empty($filters) && !empty($filters['name'])) {
            $users->where('name', 'LIKE', '%'.$filters['name'].'%');
        }
        if (!empty($filters) && isset($filters['status']) && $filters['status']!='') {
            $users->where('status', $filters['status']);
        }
        if (!empty($filters) && isset($filters['deleted']) && $filters['deleted']) {
            $users->withTrashed();
        }
        $users = $users->paginate($filters['perpage'], null, 'page', !empty($filters['page']) ? $filters['page'] : null);

        $groups = UserGroup::orderBy('name_rus')->get();

        return view('admin.users.index', ['users' => $users, 'groups' => $groups, 'filters' => $filters]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = UserGroup::orderBy('name_rus')->get();
        return view('admin.users.create', ['groups' => $groups]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\admin\UserRequest $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        User::create($data);
        return redirect()->route('admin.users.index')->withMessage('Пользователь зарегистрирован');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $groups = UserGroup::orderBy('name_rus')->get();
        return view('admin.users.edit', ['user' => $user, 'groups' => $groups]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\admin\UserEditRequest $request, $id)
    {
        $user = User::findOrFail($id);
        //проверка прав на изменение маршрута
        if($request->has('password')) {
            $data = $request->all();
            $data['password'] = bcrypt($data['password']);
            $user->update($data);
        } else {
            $user->update(array_except($request->input(), ['password']));
        }
        return redirect()->route('admin.users.index')->withMessage('Пользователь изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('admin.users.index')->withMessage('Пользователь удален');
    }


    /**
     * Востановление мягко удаленной категории
     * @param $id
     * @return mixed
     */
    public function restore($id) {
        User::withTrashed()->find($id)->restore();
        return redirect()->route('admin.users.index')->withMessage('Пользователь востановлен');
    }
}
