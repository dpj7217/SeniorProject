<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class vueController extends Controller
{
    public function test() {
        return view('vueTester'); 
    }
}
