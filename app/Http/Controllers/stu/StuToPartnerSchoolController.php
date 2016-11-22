<?php

namespace App\Http\Controllers\stu;

use Illuminate\Http\Request;
use App\StuToPartnerSchool;
class StuToPartnerSchoolController extends Controller
{
    //
    public function index (){
    	$topartnerdata = StuToPartnerSchool::paginate(20);
		$data = compact('topartnerdata');
		return view('stu/stu_to_partner_school',$data);
    	}
}
