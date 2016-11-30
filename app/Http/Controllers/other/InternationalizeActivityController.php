<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InternationalizeActivity;
class InternationalizeActivityController extends Controller
{
    //
    public function index(){

    	$internationalactivity= InternationalizeActivity::join('college_data',function($join){
    		$join->on('internationalize_activity.college','college_data.college');
    		$join->on('internationalize_activity.dept','college_data.dept');
    		})->paginate(20);
    	$data=compact('internationalactivity');
    	return view ('other/internationalize_activity',$data);
    }
}
