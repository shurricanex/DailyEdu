<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
class ImportExportController extends Controller
{
    public function export(){
         Excel::download(new UsersExport,'user.xlsx');
         return response()->json(['message'=>'Sucessfully exported']);
    }
    public function  import(){
         Excel::import(new UsersImport,request()->file('import_file'));
         return response()->json(['message'=>'Sucessfully imported']);

    }
}
