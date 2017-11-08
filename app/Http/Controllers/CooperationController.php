<?php

namespace App\Http\Controllers;

use App\Http\Requests\CooperationRequest;
use App\Models\Cooperation;
use Illuminate\Http\Request;

class CooperationController extends Controller
{
    //
    public function create(CooperationRequest $request) {

        $model = Cooperation::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ]);
        if($model) {
            return [
                'action' => 'openModal',
                'modal' => view('modals.letter_success')->render(),
            ];
        } else {
            return [
                'action' => 'openModal',
                'modal' => view('modals.letter_fail')->render(),
            ];
        }

    }
}
