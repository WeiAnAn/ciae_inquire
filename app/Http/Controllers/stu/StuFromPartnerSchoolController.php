<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\StuFromPartnerSchool;

class StuFromPartnerSchoolController extends Controller
{
    //
    public function index(){
		$frompartnerdata = StuFromPartnerSchool::paginate(20);
		$data = compact('frompartnerdata');
		return view('stu/stu_from_partner_school',$data);
	}
}
