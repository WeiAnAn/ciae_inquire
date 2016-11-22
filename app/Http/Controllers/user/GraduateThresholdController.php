<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GraduateThreshold;

class GraduateThresholdController extends Controller
{
    //
    public function index(){

    	$graduateThreshold = GraduateThreshold::join('college_data',function($join){
    		$join->on('graduate_threshold.college','college_data.college');
    		$join->on('graduate_threshold.dept','college_data.dept');
    	})->paginate(20);
    	
    	$data=compact('graduateThreshold');
    	return view ('user/graduate_threshold',$data);

    }
}
