<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CooperationProj;
class CooperationProjController extends Controller
{
    //
    public function index(){

    	$cooperationproj= CooperationProj::paginate(20);
    	$data=compact('cooperationproj');
    	return view ('other/cooperation_proj',$data);
    }
}
