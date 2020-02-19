<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountantController extends Controller
{
    public function welcome(){
    	if (session()->has('fullname')) {
             return view('accountants_dashboard');
        }
         return redirect('/'); 	
    }
}
