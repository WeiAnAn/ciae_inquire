<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ForeignStu;

class ForeignStuController extends Controller
{
    //
    public function index(){
    	$foreignStu = ForeignStu::paginate(20);
    	$data = compact('foreignStu');
    	return view ('stu/foreign_stu',$data);

    }
}
