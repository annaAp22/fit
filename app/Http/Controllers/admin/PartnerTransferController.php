<?php

namespace App\Http\Controllers\admin;

use App\Models\PartnerTransfer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PartnerTransferController extends Controller
{
    protected $per_page = 24;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $list = PartnerTransfer::with([
            'partner' => function($query) {
                $query->with('user');
            },
            'operator'
        ])->paginate($this->per_page);
        return view('admin.partner_transfer.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PartnerTransfer  $partnerTransfer
     * @return \Illuminate\Http\Response
     */
    public function show(PartnerTransfer $partnerTransfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PartnerTransfer  $partnerTransfer
     * @return \Illuminate\Http\Response
     */
    public function edit(PartnerTransfer $partnerTransfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PartnerTransfer  $partnerTransfer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PartnerTransfer $partnerTransfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PartnerTransfer  $partnerTransfer
     * @return \Illuminate\Http\Response
     */
    public function destroy(PartnerTransfer $partnerTransfer)
    {
        //
    }
}
