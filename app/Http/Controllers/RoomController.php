<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    //
    public function index() {
        $user = Auth::user();
        if(!$user) {
            abort('404');
        }
        return view('content.room', compact('user'));
    }
}
