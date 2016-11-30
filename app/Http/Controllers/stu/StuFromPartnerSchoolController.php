<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\StuFromPartnerSchool;

class StuFromPartnerSchoolController extends Controller
{
    //
    public function index(){
		$frompartnerdata = StuFromPartnerSchool::join('college_data',function($join){
    		$join->on('stu_from_partner_school.college','college_data.college');
    		$join->on('stu_from_partner_school.dept','college_data.dept');
    		})->paginate(20);
		$data = compact('frompartnerdata');
		return view('stu/stu_from_partner_school',$data);
	}
}
