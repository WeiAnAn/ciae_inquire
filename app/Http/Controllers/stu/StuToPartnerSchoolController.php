<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\StuToPartnerSchool;
class StuToPartnerSchoolController extends Controller
{
    //
    public function index (){
    	$topartnerdata = StuToPartnerSchool::join('college_data',function($join){
    		$join->on('stu_to_partner_school.college','college_data.college');
    		$join->on('stu_to_partner_school.dept','college_data.dept');
    		})->paginate(20);
		$data = compact('topartnerdata');
		return view('stu/stu_to_partner_school',$data);
    	}
}
