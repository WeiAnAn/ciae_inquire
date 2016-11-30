<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StuForeignResearch;

class StuForeignResearchController extends Controller
{
    //
     public function index (){
    	$foreignreseach = StuForeignResearch::join('college_data',function($join){
    		$join->on('stu_foreign_research.college','college_data.college');
    		$join->on('stu_foreign_research.dept','college_data.dept');
    		})->paginate(20);
		$data = compact('foreignreseach');
		return view('stu/stu_foreign_research',$data);
    	}
}
