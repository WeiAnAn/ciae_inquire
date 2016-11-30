<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PartnerSchool;
class PartnerSchoolController extends Controller
{
    //
    public function index (){
    	$partner= PartnerSchool::join('college_data',function($join){
    		$join->on('partner_school.college','college_data.college');
    		$join->on('partner_school.dept','college_data.dept');
    		})->paginate(20);
    	$data=compact('partner');
    	return view ('other/partner_school',$data);
    }
}
