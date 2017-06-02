<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgencyController extends Controller
{
    //
    function index() {
      return view('agency.index');
    }
    function detail() {
      return view('agency.detail');
    }
}
