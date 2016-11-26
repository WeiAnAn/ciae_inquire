<?php

namespace App\Http\Controllers\prof;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProfAttendConference;
class ProfAttendConferenceController extends Controller
{
    //
    public function index(){

    	$Pattendconference=ProfAttendConference::paginate(20);
    	$data=compact('Pattendconference');
    	return view ('prof/prof_attend_conference',$data);
    }
}
