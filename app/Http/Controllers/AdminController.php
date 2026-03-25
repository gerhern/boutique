<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{
    public function dashboard(Request $request){
        $user = $request->user();

        if(!$user->isAdmin()){
            return Redirect::route('products.index');
        }
    }
}
