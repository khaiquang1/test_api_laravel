<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TestMulti;

class TestController extends Controller
{
    public function index(){
        $lists = TestMulti::where('status',1)->root()->get();

        return view('test.multi-options', [
            'lists' => $lists,
        ]);
    }
}
