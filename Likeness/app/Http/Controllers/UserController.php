<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($user) {
        return view('users/user', [
            'user' => $user
        ]);
    }


    public function create($user) {
        
    }


    public function update($user) {

    }



    public function delete($user) {
        
    }


}
