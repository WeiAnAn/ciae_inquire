<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\StuAttendConf;
class StuAttendConfController extends Controller
{
    //
    public function index (){
    	$conf = StuAttendConf::paginate(20);

    	$data=compact('conf');
    	return view ('stu/stu_attend_conf',$data);

    	}
}
