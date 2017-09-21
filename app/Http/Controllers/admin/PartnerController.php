<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\admin\PartnerRequest;
use App\Models\Partner;
use App\Models\PartnerTransfer;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PartnerController extends Controller
{
    private $per_page = 24;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $partners = Partner::paginate($this->per_page);
        return view('admin.partners.index', compact('partners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $users = User::published()->get();
        return view('admin.partners.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PartnerRequest $request)
    {
        $request->request->add([
            'code' => Partner::generateSaleCode(),
        ]);
        $request->validate();
        $data = $request->all();
        $partner = Partner::create($data);
        return redirect()->route('admin.partners.index')->withMessage('Партнер "'.$partner->user->name.'" создан');

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
        $partner = Partner::find($id);
        return view('admin.partners.show', compact('partner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $partner = Partner::find($id);
        return view('admin.partners.edit', compact('partner'));
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
        $partners = Partner::all();
        $partner = $partners->find($id);
        if(!$partner) {
            return redirect()->route('admin.partners.index', compact('partners'))->withMessage('Партнер не найден');
        }
        $rules = [
            'money' => 'required|numeric|min:0,max:'.$partner->remain_money,
            'partner_id' => 'required|integer|min:1',
        ];
        $data = [
            'partner_id' => $partner->id,
            'manager_id' => $request->input('manager_id'),
            'status' => 'withdraw',
            'money' => $request->input('withdraw_money'),
        ];
        Validator::make($data, $rules)->validate();
        if(isset($data['manager_id'])) {
            $partner->withdraw($data['money']);
            $partner->save();
            PartnerTransfer::create($data);
        }
        return redirect()->route('admin.partners.index', compact('partners'))->withMessage('Данные отредактированы');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $partner = Partner::find($id);
        $partner->destroy($id);
        $partners = Partner::paginate($this->per_page);
        return redirect()->route('admin.partners.index', compact('partners'))->withMessage('Партнер "'.$partner->user->name.'" удален');
    }
}
