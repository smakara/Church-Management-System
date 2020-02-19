<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Input;
use App\Post;
use App\BuildMembers;
use DB;
use Session;
use Excel;

class BuildMaatwebsiteController extends Controller
{
    public function importExport()
    {
        return view('importExport');
    }
    public function downloadExcel($type)
    {
        $data = BuildMembers::where('mem_conference','=','BUILD Conference 2018')->orderBy('mem_gender', 'DESC')->get()->toArray();
        // $data = BuildMembers::where('mem_conference','=','BUILD Conference 2018')
        //             ->where('mem_accomodation','=','Private')
        //             ->where('mem_bod','=','Day')->get()->toArray();
        return Excel::create('BUILD Conference 2018', function($excel) use ($data) {
            $excel->sheet('BUILD Conference 2018', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
                $sheet->setAutoSize(true);
            });
        })->download($type);
    }
    public function importExcel(Request $request)
    {
        date_default_timezone_set('Africa/Nairobi');
        if($request->hasFile('import_file')){
            Excel::load($request->file('import_file')->getRealPath(), function ($reader) {
                foreach ($reader->toArray() as $key => $row) {
                    $data['mem_firstname'] = $row['mem_firstname'];
                    $data['mem_middlename'] = $row['mem_middlename'];
                    $data['mem_lastname'] = $row['mem_lastname'];
                    $data['mem_gender'] = $row['mem_gender'];
                    $data['mem_age'] = $row['mem_age'];
                    $data['mem_mobile'] = $row['mem_mobile'];
                    $data['mem_whatsapp'] = $row['mem_whatsapp'];
                    $data['mem_email'] = $row['mem_email'];
                    $data['mem_church'] = $row['mem_church'];
                    $data['mem_region'] = $row['mem_region'];
                    $data['mem_country'] = $row['mem_country'];
                    $data['mem_positioninchurch'] = $row['mem_positioninchurch'];
                    $data['mem_bod'] = $row['mem_bod'];
                    $data['mem_allergy'] = $row['mem_allergy'];
                    $data['mem_accomodation'] = $row['mem_accomodation'];
                    $data['mem_conference'] = 'BUILD Conference 2018';
                    $data['mem_registrationfee'] = $row['mem_registrationfee'];
                    // $data['registered_at'] = date('Y-m-d H:i:s');
                    $data['registered_at'] = '2018-08-13';
                    $data['mem_user'] = Session::get('fullname');
                     if(!empty($data)) {
                        DB::table('build_members')->insert($data);
                    }
                }
            });
        }

        Session::put('success', 'File successfully imported in database!!!');

        return back();
    }

    // public function exportUserData($type)
    // {
    // 	$data = User::get()->toArray();
    //     return Excel::create('laravelcode', function($excel) use ($data) {
    //         $excel->sheet('mySheet', function($sheet) use ($data)
    //         {
    //             $sheet->cell('A1', function($cell) {$cell->setValue('First Name');   });
    //             $sheet->cell('B1', function($cell) {$cell->setValue('Last Name');   });
    //             $sheet->cell('C1', function($cell) {$cell->setValue('Email');   });
    //             if (!empty($data)) {
    //                 foreach ($data as $key => $value) {
    //                     $i= $key+2;
    //                     $sheet->cell('A'.$i, $value['firstname']); 
    //                     $sheet->cell('B'.$i, $value['lastname']); 
    //                     $sheet->cell('C'.$i, $value['email']); 
    //                 }
    //             }
    //         });
    //     })->download($type);
    // }
}