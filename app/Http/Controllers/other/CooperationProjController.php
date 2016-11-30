<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CooperationProj;
class CooperationProjController extends Controller
{
    //
    public function index(){

    	$cooperationproj= CooperationProj::join('college_data',function($join){
    		$join->on('cooperation_proj.college','college_data.college');
    		$join->on('cooperation_proj.dept','college_data.dept');
    		})->paginate(20);
    	$data=compact('cooperationproj');
    	return view ('other/cooperation_proj',$data);
    }
}
