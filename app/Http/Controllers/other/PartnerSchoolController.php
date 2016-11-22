<?php

namespace App\Http\Controllers\other;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PartnerSchool;
class PartnerSchoolController extends Controller
{
    //
    public function index (){
    	$partner= PartnerSchool::paginate(20);
    	$data=compact('partner');
    	return view ('other/partner_school',$data);
    }
}
