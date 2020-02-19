<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use DB;
use Session;
use App\TIFUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller {

    public function login() {
        $inputs = array(
            "username" => Input::get('email'),
            "password" => Input::get('password')
        );
        $u = DB::table('build_members')
                ->where('mem_username', '=', Input::get('email'))
                ->where('mem_password', '=', Input::get('password'))
                ->get();
        if (sizeof($u) == 1) {
            //$role = DB::table('nad_tifusers')->where('Username', $inputs['username'])->value('UserType');
            //$userDesignation = DB::table('nad_tifusers')->where('Username', $inputs['username'])->value('Designation');
            /////$userlevel = DB::table('nad_tifusers')->where('Username', $inputs['username'])->value('Userlevel');
            //$loggedusername = DB::table('nad_tifusers')->where('Username', $inputs['username'])->value('Username');
            //$dbfname = DB::table('nad_tifusers')->where('Username', $inputs['username'])->value('FName');
            //$dblname = DB::table('nad_tifusers')->where('Username', $inputs['username'])->value('LName');
            //$dbname = $dbfname.' '.$dblname;
            $user = DB::table('build_members')
                    ->where('mem_username', '=', Input::get('email'))
                    ->where('mem_password', '=', Input::get('password'))
                    ->first();
            $role = $user->mem_positioninchurch;
//            if ($role == 'Admin' || $role == 'Pastor' || $role == 'Bishop' || $role == 'Overseer') {

            Session::put('fullname', $user->mem_firstname . " " . $user->mem_lastname);
            Session::put('username', $user->mem_username);
            Session::put('userDesignation', $user->mem_positioninchurch);
             Session::put('memberid', $user->mem_id);
            Session::put('level', 1);
            Session::put('role', $user->mem_positioninchurch);
            return response()->json(['redirect' => 'dashboard']);
//            } else {
//            }
//            } else if ($role == 'Registrar') {
//                Session::put('fullname', $dbname);
//                Session::put('username', $loggedusername);
//                Session::put('userDesignation', $userDesignation);
//                Session::put('level', $userlevel);
//                return response()->json(['redirect' => 'dashboard2']);
//            } else if ($role == 'Badges') {
//                Session::put('fullname', $dbname);
//                Session::put('username', $loggedusername);
//                Session::put('userDesignation', $userDesignation);
//                Session::put('level', $userlevel);
//                return response()->json(['redirect' => 'secretary']);
        } else {
            return redirect('/');
        }
    }

    public function loginView() {
        return view('homepage_login');
    }

    public function getLogout() {
        Auth::logout();
        Session::flush();
        return redirect('/');
    }

}
