<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use DB;
use Session;
use App\BuildMembers;
use App\BuildCountry;
use App\BuildRegions;
use App\BuildChurches;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use infobip\api\client\SendSingleTextualSms;
use infobip\api\configuration\BasicAuthConfiguration;
use infobip\api\model\sms\mt\send\textual\SMSTextualRequest;

class DashboardController extends Controller {

    public function welcome() {
        if (session()->has('fullname')) {
            $memberCountries = DB::select(DB::raw("SELECT distinct(`name`) FROM `build_countries` order by `name` asc"));
            $memberChurches = DB::select(DB::raw("SELECT distinct(`mem_church`) FROM `build_members` order by `mem_church` asc"));
            $memberRegions = DB::select(DB::raw("SELECT distinct(`reg_name`) FROM `build_regions` order by `reg_name` asc"));
            $activeEvent = DB::select(DB::raw("SELECT distinct(`eventname`) FROM `cims_events`where `status`='active' LIMIT 1 "));

            $countMembers = DB::table('build_members')
                    ->count();

            $countCountries = DB::table('build_members')
                            ->distinct()->get(['mem_country'])->count();
            // ->count(DB::raw('DISTINCT mem_country'));

            $countChurches = DB::table('build_members')
                            ->distinct()->get(['mem_church'])->count();
            //  $countgender = DB::table('build_members')->distinct()->get(['mem_gender'])->count();
            $countMales = BuildMembers::where('mem_gender', 'Like', 'M%')
                    ->count();

            $countFemales = BuildMembers::where('mem_gender', 'Like', 'F%')
                    ->count();

            $countMalesTz = BuildMembers::where('mem_gender', 'Like', 'M%')
                            ->where('mem_country', '=', 'TANZANIA')
                            ->where('mem_conference', '=', 'BUILD Conference 2017')->count();

            $countFemalesTz = BuildMembers::where('mem_gender', 'Like', 'F%')
                            ->where('mem_country', '=', 'TANZANIA')
                            ->where('mem_conference', '=', 'BUILD Conference 2017')->count();

            $countMalesKn = BuildMembers::where('mem_gender', '=', 'M')
                            ->where('mem_country', '=', 'KENYA')
                            ->where('mem_conference', '=', 'BUILD Conference 2017')->count();

            $countFemalesKn = BuildMembers::where('mem_gender', '=', 'F')
                            ->where('mem_country', '=', 'KENYA')
                            ->where('mem_conference', '=', 'BUILD Conference 2017')->count();

            $countMalesUg = BuildMembers::where('mem_gender', '=', 'M')
                            ->where('mem_country', '=', 'UGANDA')
                            ->where('mem_conference', '=', 'BUILD Conference 2017')->count();

            $countFemalesUg = BuildMembers::where('mem_gender', '=', 'F')
                            ->where('mem_country', '=', 'UGANDA')
                            ->where('mem_conference', '=', 'BUILD Conference 2017')->count();

            $countMalesBn = BuildMembers::where('mem_gender', '=', 'M')
                            ->where('mem_country', '=', 'BURUNDI')
                            ->where('mem_conference', '=', 'BUILD Conference 2017')->count();

            $countFemalesBn = BuildMembers::where('mem_gender', '=', 'F')
                            ->where('mem_country', '=', 'BURUNDI')
                            ->where('mem_conference', '=', 'BUILD Conference 2017')->count();

            $countMalesRn = BuildMembers::where('mem_gender', '=', 'M')
                            ->where('mem_country', '=', 'RWANDA')
                            ->where('mem_conference', '=', 'BUILD Conference 2017')->count();

            $countFemalesRn = BuildMembers::where('mem_gender', 'LIKE', 'F%')
                            ->where('mem_country', '=', 'RWANDA')
                            ->where('mem_conference', '=', 'BUILD Conference 2017')->count();
            // foreach($memberCountries as $memberCountry){
            //     $countryFemales = BuildMembers::where('mem_gender','=','F')
            //     ->where('mem_country','=',$memberCountry['mem_country'])->count();
            // }

            $allmembers = DB::select(DB::raw("SELECT * FROM build_members"));
            return view('dashboard', compact('allmembers', 'countMalesKn', 'countMalesBn', 'countMalesRn', 'countMalesUg', 'countMalesTz', 'countFemalesKn', 'countFemalesBn', 'countFemalesRn', 'countFemalesUg', 'countFemalesTz', 'countMalesTz', 'countMembers', 'countCountries', 'countMales', 'countFemales', 'countChurches', 'memberCountries', 'memberChurches', 'memberRegions', 'activeEvent'));
        }
        return redirect('/');
    }

    public function getDashboard() {
        if (session()->has('fullname')) {


            $countMembers = BuildMembers::where('registered_at', 'LIKE', '2018%')->count();

            // }
            return response()->json(['redirect' => 'dashboard2']);
            // $allmembers = DB::select(DB::raw("SELECT * FROM `build_members`"));
            // $returnHTML= view('dashboard',compact('allmembers','countMalesKn','countMalesBn','countMalesRn','countMalesUg','countMalesTz','countFemalesKn','countFemalesBn','countFemalesRn','countFemalesUg','countFemalesTz','countMalesTz','countMembers','countCountries','countMales','countFemales','countChurches','memberCountries','memberChurches','memberRegions'))->render();
            //  response()->json(array('success' => true, 'html'=>$returnHTML));
            // $allmembers = DB::select(DB::raw("SELECT * FROM `build_members`"));
        }
        return redirect('/');
    }

    public function getDashboard2() {
        if (session()->has('fullname')) {
            $memberCountries = DB::select(DB::raw("SELECT distinct(`name`) FROM `build_countries`  order by `name` asc"));
            $memberChurches = DB::select(DB::raw("SELECT distinct(`church_abbreviation`) FROM `build_churches` order by `church_abbreviation` asc"));
            $memberRegions = DB::select(DB::raw("SELECT distinct(`reg_name`) FROM `build_regions` order by `reg_name` asc"));
            $memberRoles = DB::select(DB::raw("SELECT distinct(`mem_rolename`) FROM `build_churchroles` order by `mem_rolename` asc"));
            $activeEvent = DB::select(DB::raw("SELECT distinct(`eventname`) FROM `cims_events`where `status`='active' LIMIT 1 "));
            $countMembers = BuildMembers::where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'BUILD Conference 2018')->count();
            $pwd = Hash::make('melody2018');
            $countCountries = DB::table('build_members')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'BUILD Conference 2018')
                            ->distinct()->get(['mem_country'])->count();

            $countChurches = DB::table('build_members')
                            ->distinct()->get(['mem_church'])->count();
            //  $countgender = DB::table('build_members')->distinct()->get(['mem_gender'])->count();
            $countMales = BuildMembers::where('mem_gender', '=', 'Male')
                    ->where('registered_at', 'LIKE', '2018%')
                    ->where('mem_conference', '=', 'BUILD Conference 2018')
                    ->count();

            $countFemales = BuildMembers::where('mem_gender', '=', 'Female')
                    ->where('registered_at', 'LIKE', '2018%')
                    ->where('mem_conference', '=', 'BUILD Conference 2018')
                    ->count();

            $countMalesTz = BuildMembers::where('mem_gender', '=', 'Male')
                    ->where('registered_at', 'LIKE', '2018%')
                    ->where('mem_country', '=', 'TANZANIA')
                    ->where('mem_conference', '=', 'BUILD Conference 2018')
                    ->count();

            $countFemalesTz = BuildMembers::where('mem_gender', '=', 'Female')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'BUILD Conference 2018')
                            ->where('mem_country', '=', 'TANZANIA')->count();

            $countMalesKn = BuildMembers::where('mem_gender', '=', 'Male')
                    ->where('mem_country', '=', 'KENYA')
                    ->where('mem_conference', '=', 'BUILD Conference 2018')
                    ->where('registered_at', 'LIKE', '2018%')
                    ->count();

            $countFemalesKn = BuildMembers::where('mem_gender', '=', 'Female')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'BUILD Conference 2018')
                            ->where('mem_country', '=', 'KENYA')->count();

            $countMalesUg = BuildMembers::where('mem_gender', '=', 'Male')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'BUILD Conference 2018')
                            ->where('mem_country', '=', 'UGANDA')->count();

            $countFemalesUg = BuildMembers::where('mem_gender', '=', 'Female')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'BUILD Conference 2018')
                            ->where('mem_country', '=', 'UGANDA')->count();

            $countMalesBn = BuildMembers::where('mem_gender', '=', 'Male')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'BUILD Conference 2018')
                            ->where('mem_country', '=', 'BURUNDI')->count();

            $countFemalesBn = BuildMembers::where('mem_gender', '=', 'Female')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'BUILD Conference 2018')
                            ->where('mem_country', '=', 'BURUNDI')->count();

            $countMalesRn = BuildMembers::where('mem_gender', '=', 'Male')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'BUILD Conference 2018')
                            ->where('mem_country', '=', 'RWANDA')->count();

            $countFemalesRn = BuildMembers::where('mem_gender', '=', 'Female')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'BUILD Conference 2018')
                            ->where('mem_country', '=', 'RWANDA')->count();

            $countFemalesCn = BuildMembers::where('mem_gender', '=', 'Female')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'BUILD Conference 2018')
                            ->where('mem_country', '=', 'Congo')->count();

            $countFemalesMs = BuildMembers::where('mem_gender', '=', 'Female')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'BUILD Conference 2018')
                            ->where('mem_country', '=', 'Mauritius')->count();

            $countFemalesZim = BuildMembers::where('mem_gender', '=', 'Female')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'BUILD Conference 2018')
                            ->where('mem_country', '=', 'Zimbabwe')->count();

            $countFemalesEthio = BuildMembers::where('mem_gender', '=', 'Female')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'BUILD Conference 2018')
                            ->where('mem_country', '=', 'Ethiopia')->count();

            $countMalesZim = BuildMembers::where('mem_gender', '=', 'Male')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'BUILD Conference 2018')
                            ->where('mem_country', '=', 'Zimbabwe')->count();

            $countMalesEthio = BuildMembers::where('mem_gender', '=', 'Male')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'BUILD Conference 2018')
                            ->where('mem_country', '=', 'Ethiopia')->count();


            $countMalesCn = BuildMembers::where('mem_gender', '=', 'Male')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'BUILD Conference 2018')
                            ->where('mem_country', '=', 'Congo')->count();

            $countMalesMs = BuildMembers::where('mem_gender', '=', 'Male')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'BUILD Conference 2018')
                            ->where('mem_country', '=', 'Mauritius')->count();
            //  response()->json(array('success' => true, 'html'=>$returnHTML));
            $allmembers = DB::select(DB::raw("SELECT * FROM `build_members` where registered_at like '%2018%' AND mem_conference =  'BUILD Conference 2018'"));
            return view('dashboard2', compact('countMalesEthio', 'countFemalesEthio', 'countMalesZim', 'countFemalesZim', 'pwd', 'countMalesMs', 'countFemalesMs', 'countMalesCn', 'countFemalesCn', 'allmembers', 'memberRoles', 'countMalesKn', 'countMalesBn', 'countMalesRn', 'countMalesUg', 'countMalesTz', 'countFemalesKn', 'countFemalesBn', 'countFemalesRn', 'countFemalesUg', 'countFemalesTz', 'countMalesTz', 'countMembers', 'countCountries', 'countMales', 'countFemales', 'countChurches', 'memberCountries', 'memberChurches', 'memberRegions', 'activeEvent'));

            //  response()->json(array('success' => true, 'html'=>$returnHTML));
        }
        return redirect('/');
    }

    public function getZGACTDashboard() {
        if (session()->has('fullname')) {
            $memberCountries = DB::select(DB::raw("SELECT distinct(`name`) FROM `build_countries` order by `name` asc"));
            $memberChurches = DB::select(DB::raw("SELECT distinct(`mem_church`) FROM `build_members` order by `mem_church` asc"));
            $memberRegions = DB::select(DB::raw("SELECT distinct(`reg_name`) FROM `build_regions` order by `reg_name` asc"));
            $activeEvent = DB::select(DB::raw("SELECT distinct(`eventname`) FROM `cims_events`where `status`='active' LIMIT 1 "));
            $countMembers = BuildMembers::where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'ZGACT Youth Camp')->count();

            $countCountries = DB::table('build_members')
                            ->where('registered_at', 'LIKE', '2019%')
                            ->where('mem_conference', '=', 'ZGACT Youth Camp')
                            ->distinct()->get(['mem_country'])->count();

            $countChurches = DB::table('build_members')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'ZGACT Youth Camp')
                            ->distinct()->get(['mem_church'])->count();
            //  $countgender = DB::table('build_members')->distinct()->get(['mem_gender'])->count();
            $countMales = BuildMembers::where('mem_gender', 'Like', 'M%')
                    ->where('registered_at', 'LIKE', '2018%')
                    ->where('mem_conference', '=', 'ZGACT Youth Camp')
                    ->count();

            $countFemales = BuildMembers::where('mem_gender', 'Like', 'F%')
                    ->where('registered_at', 'LIKE', '2018%')
                    ->where('mem_conference', '=', 'ZGACT Youth Camp')
                    ->count();

            $countMalesTz = BuildMembers::where('mem_gender', 'Like', 'M%')
                    ->where('registered_at', 'LIKE', '2018%')
                    ->where('mem_conference', '=', 'ZGACT Youth Camp')
                    ->where('mem_country', '=', 'TANZANIA')
                    ->count();

            $countFemalesTz = BuildMembers::where('mem_gender', '=', 'F')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'ZGACT Youth Camp')
                            ->where('mem_country', '=', 'TANZANIA')->count();

            $countMalesKn = BuildMembers::where('mem_gender', 'Like', 'M%')
                    ->where('mem_region', '=', 'Dar es Salaam')
                    ->where('mem_conference', '=', 'ZGACT Youth Camp')
                    ->where('registered_at', 'LIKE', '2018%')
                    ->count();

            $countFemalesKn = BuildMembers::where('mem_gender', 'Like', 'F%')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'ZGACT Youth Camp')
                            ->where('mem_region', '=', 'Dar es Salaam')->count();

            $countMalesUg = BuildMembers::where('mem_gender', 'Like', 'M%')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'ZGACT Youth Camp')
                            ->where('mem_region', '=', 'Mwanza')->count();

            $countFemalesUg = BuildMembers::where('mem_gender', 'Like', 'F%')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'ZGACT Youth Camp')
                            ->where('mem_region', '=', 'Mwanza')->count();

            $countMalesBn = BuildMembers::where('mem_gender', 'Like', 'M%')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'ZGACT Youth Camp')
                            ->where('mem_region', '=', 'Morogoro')->count();

            $countFemalesBn = BuildMembers::where('mem_gender', 'Like', 'F%')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'ZGACT Youth Camp')
                            ->where('mem_region', '=', 'Morogoro')->count();

            $countMalesRn = BuildMembers::where('mem_gender', 'Like', 'M%')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'ZGACT Youth Camp')
                            ->where('mem_region', '=', 'Kilimanjaro')->count();

            $countFemalesRn = BuildMembers::where('mem_gender', 'LIKE', 'F%')
                            ->where('registered_at', 'LIKE', '2018%')
                            ->where('mem_conference', '=', 'ZGACT Youth Camp')
                            ->where('mem_region', '=', 'Kilimanjaro')->count();
            //  response()->json(array('success' => true, 'html'=>$returnHTML));
            $allmembers = DB::select(DB::raw("SELECT * FROM `build_members` where `registered_at` like '%2018%' and `mem_conference`= 'ZGACT Youth Camp'"));
            return view('dashboardzgact', compact('allmembers', 'countMalesKn', 'countMalesBn', 'countMalesRn', 'countMalesUg', 'countMalesTz', 'countFemalesKn', 'countFemalesBn', 'countFemalesRn', 'countFemalesUg', 'countFemalesTz', 'countMalesTz', 'countMembers', 'countCountries', 'countMales', 'countFemales', 'countChurches', 'memberCountries', 'memberChurches', 'memberRegions', 'activeEvent'));

            //  response()->json(array('success' => true, 'html'=>$returnHTML));
        }
        return redirect('/');
    }

    public function user() {
        if (session()->has('fullname')) {
            return view('user');
        }
        return redirect('/');
    }

    public function autoComplete(Request $request) {
        $query = $request->get('term', '');

        $products = BuildMembers::where('mem_firstname', 'LIKE', '%' . $query . '%')->get();

        $userDetails = array();
        foreach ($products as $product) {

            $userDetails[] = array('value' => $product->mem_firstname . " " . $product->mem_lastname, 'id' => $product->mem_id);
        }
        if (count($userDetails))
            return $userDetails;
        else
            return ['value' => 'No Result Found', 'id' => ''];
    }

    public function deleteMember(Request $request) {
        try {
            date_default_timezone_set('Africa/Nairobi');
            $date = date('Y-m-d H:i:s');
            $deleted = BuildMembers::where('mem_id', $request->memid)->delete();
            if ($deleted)
                return response()->json(array('sms' => '1'));
        } catch (Exception $e) {
            return response()->json(array('sms' => 'Information NOT saved!!'));
        }
    }

    public function getMembers(Request $request) {
        $memberCountries = DB::select(DB::raw("SELECT distinct(`name`) FROM `build_countries`  order by `name` asc"));
        $memberChurches = DB::select(DB::raw("SELECT distinct(`mem_church`) FROM `build_members` order by `mem_church` asc"));
        $memberRegions = DB::select(DB::raw("SELECT distinct(`reg_name`) FROM `build_regions` order by `reg_name` asc"));
        $memberRoles = DB::select(DB::raw("SELECT distinct(`mem_rolename`) FROM `build_churchroles` order by `mem_rolename` asc"));
        $activeEvent = DB::select(DB::raw("SELECT distinct(`eventname`) FROM `cims_events`where `status`='active' LIMIT 1 "));
        $countMembers = BuildMembers::where('registered_at', 'LIKE', '2018%')
                        ->where('mem_conference', '=', 'BUILD Conference 2018')->count();

        $countCountries = DB::table('build_members')
                        ->where('mem_conference', '=', 'BUILD Conference 2018')
                        ->distinct()->get(['mem_country'])->count();

        $countChurches = DB::table('build_members')
                        ->where('registered_at', 'LIKE', '2018%')
                        ->where('mem_conference', '=', 'BUILD Conference 2018')
                        ->distinct()->get(['mem_church'])->count();
        //  $countgender = DB::table('build_members')->distinct()->get(['mem_gender'])->count();
        $countMales = BuildMembers::where('mem_gender', '=', 'Male')
                ->where('registered_at', 'LIKE', '2018%')
                ->where('mem_conference', '=', 'BUILD Conference 2018')
                ->count();

        $countFemales = BuildMembers::where('mem_gender', 'Like', 'F%')
                ->where('registered_at', 'LIKE', '2018%')
                ->where('mem_conference', '=', 'BUILD Conference 2018')
                ->count();

        $countMalesTz = BuildMembers::where('mem_gender', '=', 'Male')
                ->where('registered_at', 'LIKE', '2018%')
                ->where('mem_country', '=', 'TANZANIA')
                ->where('mem_conference', '=', 'BUILD Conference 2018')
                ->count();

        $countFemalesTz = BuildMembers::where('mem_gender', '=', 'Female')
                        ->where('registered_at', 'LIKE', '2018%')
                        ->where('mem_conference', '=', 'BUILD Conference 2018')
                        ->where('mem_country', '=', 'TANZANIA')->count();

        $countMalesKn = BuildMembers::where('mem_gender', '=', 'Male')
                ->where('mem_country', '=', 'KENYA')
                ->where('mem_conference', '=', 'BUILD Conference 2018')
                ->where('registered_at', 'LIKE', '2018%')
                ->count();

        $countFemalesKn = BuildMembers::where('mem_gender', '=', 'Female')
                        ->where('registered_at', 'LIKE', '2018%')
                        ->where('mem_conference', '=', 'BUILD Conference 2018')
                        ->where('mem_country', '=', 'KENYA')->count();

        $countMalesUg = BuildMembers::where('mem_gender', '=', 'Male')
                        ->where('registered_at', 'LIKE', '2018%')
                        ->where('mem_conference', '=', 'BUILD Conference 2018')
                        ->where('mem_country', '=', 'UGANDA')->count();

        $countFemalesUg = BuildMembers::where('mem_gender', '=', 'Female')
                        ->where('registered_at', 'LIKE', '2018%')
                        ->where('mem_conference', '=', 'BUILD Conference 2018')
                        ->where('mem_country', '=', 'UGANDA')->count();

        $countMalesBn = BuildMembers::where('mem_gender', '=', 'Male')
                        ->where('registered_at', 'LIKE', '2018%')
                        ->where('mem_conference', '=', 'BUILD Conference 2018')
                        ->where('mem_country', '=', 'BURUNDI')->count();

        $countFemalesBn = BuildMembers::where('mem_gender', '=', 'Female')
                        ->where('registered_at', 'LIKE', '2018%')
                        ->where('mem_conference', '=', 'BUILD Conference 2018')
                        ->where('mem_country', '=', 'BURUNDI')->count();

        $countMalesRn = BuildMembers::where('mem_gender', '=', 'Male')
                        ->where('registered_at', 'LIKE', '2018%')
                        ->where('mem_conference', '=', 'BUILD Conference 2018')
                        ->where('mem_country', '=', 'RWANDA')->count();

        $countFemalesRn = BuildMembers::where('mem_gender', '=', 'Female')
                        ->where('registered_at', 'LIKE', '2018%')
                        ->where('mem_conference', '=', 'BUILD Conference 2018')
                        ->where('mem_country', '=', 'RWANDA')->count();

        $countFemalesCn = BuildMembers::where('mem_gender', '=', 'Female')
                        ->where('registered_at', 'LIKE', '2018%')
                        ->where('mem_conference', '=', 'BUILD Conference 2018')
                        ->where('mem_country', '=', 'Congo')->count();

        $countFemalesMs = BuildMembers::where('mem_gender', '=', 'Female')
                        ->where('registered_at', 'LIKE', '2018%')
                        ->where('mem_conference', '=', 'BUILD Conference 2018')
                        ->where('mem_country', '=', 'Mauritius')->count();

        $countMalesCn = BuildMembers::where('mem_gender', '=', 'Male')
                        ->where('registered_at', 'LIKE', '2018%')
                        ->where('mem_conference', '=', 'BUILD Conference 2018')
                        ->where('mem_country', '=', 'Congo')->count();

        $countMalesMs = BuildMembers::where('mem_gender', '=', 'Male')
                        ->where('registered_at', 'LIKE', '2018%')
                        ->where('mem_conference', '=', 'BUILD Conference 2018')
                        ->where('mem_country', '=', 'Mauritius')->count();
        $allmembers = DB::select(DB::raw("SELECT * FROM `build_members` where registered_at like '%2018%' AND mem_conference =  'BUILD Conference 2018'"));
        return view('allmembers', compact('countMalesMs', 'countFemalesMs', 'countMalesCn', 'countFemalesCn', 'allmembers', 'memberRoles', 'countMalesKn', 'countMalesBn', 'countMalesRn', 'countMalesUg', 'countMalesTz', 'countFemalesKn', 'countFemalesBn', 'countFemalesRn', 'countFemalesUg', 'countFemalesTz', 'countMalesTz', 'countMembers', 'countCountries', 'countMales', 'countFemales', 'countChurches', 'memberCountries', 'memberChurches', 'memberRegions', 'activeEvent'));
    }

    public function saveZGACTMember(Request $request) {
        try {
            date_default_timezone_set('Africa/Nairobi');
            $date = date('Y-m-d H:i:s');
            $st = new BuildMembers;
            $st->mem_firstname = $request->firstname;
            $st->mem_lastname = $request->lastname;
            $st->mem_gender = $request->gender;
            $st->mem_mobile = $request->mobile;
            $st->mem_email = $request->email;
            $st->mem_church = $request->church;
            $st->mem_conference = $request->eventname;
            $st->mem_region = $request->region;
            $st->mem_country = $request->country;
            $st->registered_at = $date;
            $st->save();
            return response()->json(array('sms' => '1'));
        } catch (Exception $e) {
            return response()->json(array('sms' => 'Information NOT saved!!'));
        }
    }

    public function addncountry(Request $request) {
        try {
            date_default_timezone_set('Africa/Nairobi');
            $date = date('Y-m-d H:i:s');
            $st = new BuildCountry;
            $st->name = $request->countryname;
            $st->save();
            return response()->json(array('sms' => '1'));
        } catch (Exception $e) {
            return response()->json(array('sms' => 'Information NOT saved!!'));
        }
    }

    public function addnregion(Request $request) {
        try {
            date_default_timezone_set('Africa/Nairobi');
            $date = date('Y-m-d H:i:s');
            $st = new BuildRegions;
            $st->reg_name = $request->rname;
            $st->country = $request->cname;
            $st->save();
            return response()->json(array('sms' => '1'));
        } catch (Exception $e) {
            return response()->json(array('sms' => 'Information NOT saved!!'));
        }
    }

    public function addnchurch(Request $request) {
        try {
            date_default_timezone_set('Africa/Nairobi');
            $date = date('Y-m-d H:i:s');
            $st = new BuildChurches;
            $st->church_name = $request->churchname;
            $st->church_abbreviation = $request->abb;
            $st->church_region = $request->aregion;
            $st->church_country = $request->aCountry;
            $st->save();
            return response()->json(array('sms' => '1'));
        } catch (Exception $e) {
            return response()->json(array('sms' => 'Information NOT saved!!'));
        }
    }

    public function savemember(Request $request) {
        try {
            date_default_timezone_set('Africa/Harare');
            $date = date('Y-m-d');
            $st = new BuildMembers;
            $st->mem_firstname = $request->firstname;
            $st->mem_lastname = $request->lastname;
            $st->mem_gender = $request->gender;
            $st->mem_mobile = $request->mobile;
            $st->mem_email = $request->mem_email;
            $st->mem_church = $request->church;
            $st->mem_conference = $request->eventname;
            $st->mem_region = $request->region;
            $st->mem_country = $request->country;
            $st->mem_positioninchurch = $request->mem_positioninchurch;
            $st->mem_ministry = $request->mem_ministry;
            $st->mem_bod = $request->boardingday;
            $st->mem_user = Session::get('fullname');
            $st->registered_at = $date;
            $st->mem_username = $request->username;
            $st->mem_password = $request->password;

            $st->save();
            return response()->json(array('sms' => '1'));
        } catch (Exception $e) {
            return response()->json(array('sms' => 'Information NOT saved!!'));
        }
    }

    public function searchmember(Request $request) {

        $build_members = DB::table('build_members')
                ->where('mem_firstname', 'Like', "%" . $request->searchmember . "%")
                ->orWhere('mem_lastname', 'Like', "%" . $request->searchmember . "%")
                ->get();

        if ($build_members) {

            $build_members = DB::table('build_members')
                    ->where('mem_firstname', 'Like', "%" . $request->searchmember . "%")
                    ->orWhere('mem_lastname', 'Like', "%" . $request->searchmember . "%")
                    ->first();

            $data = Array(
                'name' => $build_members->mem_firstname,
                'lastname' => $build_members->mem_lastname,
                'mem_id' => $build_members->mem_id,
                'position' => $build_members->mem_positioninchurch,
                'church' => $build_members->mem_church,
                'mem_region' => $build_members->mem_region,
                'mem_mobile' => $build_members->mem_mobile
            );

            return response()->json($data);
        }
    }

    public function saveBuildMember(Request $request) {
        try {
            date_default_timezone_set('Africa/Nairobi');
            $date = date('Y-m-d H:i:s');
            $st = new BuildMembers;
            $st->mem_firstname = $request->firstname;
            $st->mem_lastname = $request->lastname;
            $st->mem_gender = $request->gender;
            $st->mem_mobile = $request->mobile;
            $st->mem_email = $request->email;
            $st->mem_church = $request->church;
            $st->mem_conference = $request->eventname;
            $st->mem_region = $request->region;
            $st->mem_country = $request->country;
            $st->mem_positioninchurch = $request->positioninchurch;
            $st->mem_allergy = $request->allergy;
            $st->mem_accomodation = $request->accomodation;
            $st->mem_bod = $request->boardingday;
            $st->mem_agegroup = $request->agegroup;
            $st->mem_user = Session::get('fullname');
            $st->registered_at = $date;
            $st->save();
            return response()->json(array('sms' => '1'));
        } catch (Exception $e) {
            return response()->json(array('sms' => 'Information NOT saved!!'));
        }
    }

    public function saveoffering(Request $request) {

        try {
            date_default_timezone_set('Africa/Harare');
            DB::table('build_offering')->insert([
                'offering_amount' => $request->offeringamount,
                'tithe_amount' => $request->titheamount,
                'ministrysupport' => $request->ministrysupport,
                'offeringdate' => date("Y-m-d", time()),
                'member_id' => $request->memberid
            ]);

            DB::table('build_other_offerings')->insert([
                'offering_other_amount' => $request->otherfundsamount,
                'offering_other_desc' => $request->otherfundsdescription,
                'offering_other_date' => date("Y-m-d", time()),
                'other_offering_member_id' => $request->memberid
            ]);


            $data = Array(
                'success' => "success"
            );
            return $data;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function memberprofile(Request $request) {

        $build_members = DB::table("build_members")
                ->join("build_offering", "build_offering.member_id", "=", "build_members.mem_id")
                ->where('build_members.mem_id', '=', $request->memberid)
                ->first();


        $build_offering = DB::table("build_offering")
                ->where('build_offering.member_id', '=', $request->memberid)
                ->OrderBy("offeringdate", "DESC")
                ->get();

        $me = DB::table('build_memberpositions')
                ->where('mp_memberid', '=', $request->memberid)
                ->get();

        $data = Array(
            'name' => $build_members->mem_firstname,
            'lastname' => $build_members->mem_lastname,
            'mem_id' => $build_members->mem_id,
            'position' => $build_members->mem_positioninchurch,
            'church' => $build_members->mem_church,
            'mem_region' => $build_members->mem_region,
            'mem_mobile' => $build_members->mem_mobile,
            'offerings' => $build_offering,
            'otherpostions' => $me
        );



        return response()->json($data);
    }

    public function savestats(Request $request) {

        date_default_timezone_set('Africa/Harare');
        DB::table('build_attedance')->insert([
            'ba_totalmen' => $request->totalmen,
            'ba_totalwomen' => $request->totalwomen,
            'ba_totalyouth' => $request->totalyouth,
            'ba_totalteens' => $request->totalteens,
            'ba_sevicetype' => $request->servicetype,
            'ba_totalhossana' => $request->totalhossana,
            'ba_date' => date("Y-m-d", time())
        ]);

        $data = Array(
            'success' => "success"
        );
        return response()->json($data);
    }

    public function attendancereport(Request $request) {

        $data = DB::select("SELECT build_attedance.ba_sevicetype as service,
       SUM(build_attedance.ba_totalmen) as  men,
       SUM(build_attedance.ba_totalwomen) as  women,
       SUM(build_attedance.ba_totalyouth) as  youth,
       SUM(build_attedance.ba_totalteens) as  teens,
       SUM(build_attedance.ba_totalhossana) as  hossan,
       
       (build_attedance.ba_totalmen+ build_attedance.ba_totalwomen+build_attedance.ba_totalyouth+build_attedance.ba_totalteens+build_attedance.ba_totalhossana) as total
  FROM churchinfo.build_attedance build_attedance
  WHERE build_attedance.ba_date BETWEEN '$request->attfromdate'
                                   AND '$request->atttodate'
 GROUP BY build_attedance.ba_sevicetype");

        $data = Array(
            'attendancereport' => $data
        );
        return response()->json($data);
    }

    public function finreport(Request $request) {


        $fin = DB::select("SELECT build_other_offerings.offering_other_desc as des,
       SUM(build_other_offerings.offering_other_amount) as amount,
       build_other_offerings.offering_other_date
  FROM churchinfo.build_other_offerings build_other_offerings
 WHERE build_other_offerings.offering_other_date BETWEEN '$request->finfromdate'
                                   AND '$request->fintodate'
GROUP BY build_other_offerings.offering_other_desc");

        $fin2 = DB::select("SELECT build_offering.build_offering_id,
       SUM(build_offering.offering_amount) AS offering,
       SUM(build_offering.tithe_amount) AS tithe,
       SUM(build_offering.ministrysupport) AS minsupport,
       build_offering.offeringdate,
       build_offering.member_id
  FROM churchinfo.build_offering build_offering
 WHERE (build_offering.offeringdate BETWEEN '$request->finfromdate'
                                        AND '$request->fintodate')");

        $data = Array(
            'finreport' => $fin,
            'offering' => $fin2[0]->offering,
            'tithe' => $fin2[0]->tithe,
            'minsupport' => $fin2[0]->minsupport
        );
        return response()->json($data);
    }

    public function memberpostions(Request $request) {

        $m = DB::table('build_memberpositions')
                ->where('mp_position', '=', $request->assignmem_positioninchurch)
                ->where('mp_memberid', '=', $request->secretmemberid)
                ->get();

        if (!sizeof($m) > 0) {
            DB::table('build_memberpositions')->insert([
                'mp_position' => $request->assignmem_positioninchurch,
                'mp_memberid' => $request->secretmemberid
            ]);
        }

        $me = DB::table('build_memberpositions')
                ->where('mp_memberid', '=', $request->secretmemberid)
                ->get();

        $data = Array(
            'positions' => $me
        );
        return redirect('/dashboard');
    }

    public function sendsmsform(Request $request) {

        $pple = DB::table('build_members')
                ->where('mem_ministry', '=', $request->mem_ministrysms)
                ->get();
        foreach ($pple as $data) {

            self::sms($data->mem_mobile, $request->smsmsg);
        }

        return $request;
    }

    public function sms($phone, $msg) {
//        $app = Appointment::findOrFail($id);
//        $app->status = "Approved";
//        $app->save();
//  
//        $date = (string) $app->day;
//        $time = (string) $app->time;
//        $cell = $app->patient->mobile;
        //return $cell;

        $client = new SendSingleTextualSms(new BasicAuthConfiguration('user', 'password'));
        $requestBody = new SMSTextualRequest();
        $requestBody->setFrom('Church');
        $requestBody->setTo([$phone]);
        $requestBody->setText($msg);

        //dd($date.' appointment for '. $time. ' was approved');

        try {
            $response = $client->execute($requestBody);
            $sentMessageInfo = $response->getMessages()[0];
//            echo "Message ID: " . $sentMessageInfo->getMessageId() . "\n";
//            echo "Receiver: " . $sentMessageInfo->getTo() . "\n";
//            echo "Message status: " . $sentMessageInfo->getStatus()->getName();
        } catch (Exception $exception) {
//            echo "HTTP status code: " . $exception->getCode() . "\n";
//            echo "Error message: " . $exception->getMessage();
        }


//        return redirect('appointments');
    }

    public function events() {

        return view('dashboard2');
    }

    public function addevent(Request $request) {

        date_default_timezone_set('Africa/Harare');
        DB::table('build_events')->insert([
            'be_event' => $request->eventsdesc,
            'be_date' => $request->eventdate,
            'be_ministry' => $request->event_mem_ministry
        ]);

        $data = Array(
            'response' => "success"
        );
        return response()->json($data);
    }

    public function getEvents() {

        $events = DB::table("build_events")
                ->get();

        $data = Array(
            'events' => $events
        );
        return response()->json($data);
    }

    public function myevents() {


        return view("dashboard3");
    }

    public function getMyEvents() {
        $memid = Session::get('memberid');
        $myevents = DB::select("SELECT *  FROM churchinfo.build_events build_events       INNER JOIN churchinfo.build_members build_members          ON (build_events.be_ministry = build_members.mem_ministry) WHERE (build_members.mem_id = '$memid') ");

        $data = Array(
            'events' => $myevents
        );
        return response()->json($data);
    }

    public function userprofile() {
        $memid = Session::get('memberid');

        $fin = DB::select("SELECT build_other_offerings.offering_other_desc as des,
       SUM(build_other_offerings.offering_other_amount) as amount,
       build_other_offerings.offering_other_date
  FROM churchinfo.build_other_offerings build_other_offerings
 WHERE build_other_offerings.other_offering_member_id ='$memid'
GROUP BY build_other_offerings.offering_other_desc");


        $fin2 = DB::select("SELECT build_offering.build_offering_id,
       SUM(build_offering.offering_amount) AS offering,
       SUM(build_offering.tithe_amount) AS tithe,
       SUM(build_offering.ministrysupport) AS minsupport,
       build_offering.offeringdate,
       build_offering.member_id
  FROM churchinfo.build_offering build_offering
 WHERE (build_offering.member_id ='$memid')");

        $fin3 = DB::select("SELECT *
  FROM churchinfo.build_offering build_offering
 WHERE (build_offering.member_id ='$memid')");


        $myevents = DB::select("SELECT *  FROM churchinfo.build_events build_events       INNER JOIN churchinfo.build_members build_members          ON (build_events.be_ministry = build_members.mem_ministry) WHERE (build_members.mem_id = '$memid') ");

        $build_members = DB::table('build_members')
                ->where('mem_id', '=', $memid)
                ->first();



        $data = Array(
            'finreport' => $fin,
            "fin3" => $fin3,
            'myevents' => $myevents,
            'offering' => $fin2[0]->offering,
            'tithe' => $fin2[0]->tithe,
            'minsupport' => $fin2[0]->minsupport,
            'name' => $build_members->mem_firstname,
            'lastname' => $build_members->mem_lastname,
            'mem_id' => $build_members->mem_id,
            'position' => $build_members->mem_positioninchurch,
            'church' => $build_members->mem_church,
            'mem_region' => $build_members->mem_region,
            'mem_mobile' => $build_members->mem_mobile
        );
//        return response()->json($data);
        return view('userprofile', $data);
    }

    public function pledge(Request $request) {

        DB::table('build_other_offerings')->insert([
            'offering_other_amount' => $request->pledgeamount,
            'offering_other_desc' => "Pledge - " . $request->pledgeevent,
            'offering_other_date' => date("Y-m-d", time()),
            'other_offering_member_id' => Session::get('memberid')
        ]);

        return response()->json($request);
    }

    public function editlogindetails(Request $request) {

        DB::table('build_members')
                ->where('mem_id', '=', Session::get('memberid'))
                ->update([
                    'mem_username' => $request->eusername,
                    'mem_password' => $request->epassword
        ]);

        return response()->json($request);
    }

}
