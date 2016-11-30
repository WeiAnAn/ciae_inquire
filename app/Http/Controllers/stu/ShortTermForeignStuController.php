<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ShortTermForeignStu;

class ShortTermForeignStuController extends Controller
{
    //
	public function index(){
		$shortterm = ShortTermForeignStu::join('college_data',function($join){
    		$join->on('short_term_foreign_stu.college','college_data.college');
    		$join->on('short_term_foreign_stu.dept','college_data.dept');
    		})->paginate(20);
		$data = compact('shortterm');
		return view('stu/short_term_foreign_stu',$data);
	}
}
