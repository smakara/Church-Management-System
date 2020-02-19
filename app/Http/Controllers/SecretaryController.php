<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SecretaryController extends Controller
{
    public function welcome(){
    	if (session()->has('fullname')) {
             return view('secretary_dashboard');
        }
         return redirect('/'); 	
    }
}
