<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\StuAttendConf;
class StuAttendConfController extends Controller
{
    //
    public function index (){
    	$conf = StuAttendConf::join('college_data',function($join){
    		$join->on('stu_attend_conf.college','college_data.college');
    		$join->on('stu_attend_conf.dept','college_data.dept');
    		})->paginate(20);
    	$data=compact('conf');
    	return view ('stu/stu_attend_conf',$data);

    	}
}
