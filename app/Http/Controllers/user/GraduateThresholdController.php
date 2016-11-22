<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GraduateThreshold;
class GraduateThresholdController extends Controller
{
    //
    public function index(){

    	$graduateThreshold=GraduateThreshold::paginate(20);
    	$data=compact('graduateThreshold');
    	return view ('user/graduate_threshold',$data);

    }
}
