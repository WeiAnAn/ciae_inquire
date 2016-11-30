<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ForeignStu;

class ForeignStuController extends Controller
{
    //
    public function index(){
    	$foreignStu = ForeignStu::join('college_data',function($join){
    		$join->on('foreign_stu.college','college_data.college');
    		$join->on('foreign_stu.dept','college_data.dept');
    		})->paginate(20);
    	$data = compact('foreignStu');
    	return view ('stu/foreign_stu',$data);

    }
}
