<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Certificate;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', new Certificate());
        $certificates = Certificate::orderBy('id','desc')->get();
        return view('admin.certificates.index', ['certificates' => $certificates]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.certificates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\admin\CertificateRequest $request)
    {
        $cert = new Certificate;

        if(!$cert->uploads->upload())
            return redirect()->route('admin.certificates.index')->with('error', 'Ошибка при загрузке изображения сертификата.');

        $cert->save();
        return redirect()->route('admin.certificates.index')->withMessage('Сертификат добавлен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $certificate = Certificate::find($id);
        $certificate->uploads->delete();
        $certificate->delete();
        return redirect()->route('admin.certificates.index')->withMessage('Сертификат удален');
    }
}
