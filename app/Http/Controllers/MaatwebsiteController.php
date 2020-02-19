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

class MaatwebsiteController extends Controller
{
    public function importExport()
    {
        return view('importExport');
    }
    public function downloadExcel($type)
    {
        $data = BuildMembers::where('mem_conference','=','ZGACT Youth Camp')->get()->toArray();
        return Excel::create('zgactmembers', function($excel) use ($data) {
            $excel->sheet('ZGACT Youth Camp', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
                $sheet->setAutoSize(true);
            });
        })->download($type);
    }
    public function importExcel(Request $request)
    {
        if($request->hasFile('import_file')){
            Excel::load($request->file('import_file')->getRealPath(), function ($reader) {
                foreach ($reader->toArray() as $key => $row) {
                    $data['title'] = $row['title'];
                    $data['description'] = $row['description'];

                    if(!empty($data)) {
                        DB::table('post')->insert($data);
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