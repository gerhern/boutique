<?php

namespace App\Http\Controllers;

use App\enums\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{
    public function dashboard(Request $request){
        $user = $request->user();

        if(!$user->isAdmin()){
            return Redirect::route('products.index');
        }

        return view('dashboard');
    }
}
