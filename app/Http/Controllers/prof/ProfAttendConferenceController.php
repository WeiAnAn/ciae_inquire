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
    public function insert(Request $request){
    	//validate 暫無失敗訊息
    	$this->validate($request,[
    		'college'=>'required|max:11',
    		'dept'=>'required|max:11',
    		'name'=>'required|max:20',
    		'profLevel'=>'required|max:11',
    		'nation'=>'required|max:20',
    		'confName'=>'required|max:200',
    		'startDate'=>'required',
    		'endDate'=>'required',
    		'comments'=>'max:500',
    		]);

    	profAttendConference::create($request->all());

    	/*
    	profAttendConference::create([
    		'college'=>$request['college'],
    		'dept'=>$request['dept'],
    		'name'=>$request['name'],
    		'profLevel'=>$request['profLevel'],
    		'nation'=>$request['nation'],
    		'confName'=>$request['confName'],
    		'startDate'=>$request['startDate'],
    		'endDate'=>$request['endDate'],
    		'comments'=>$request['comments']
    	]);
    	
    	
    	

     	$prof_attend_conference = new ProfAttendConference;

    	$prof_attend_conference->college=$request['college'];
    	$prof_attend_conference->dept=$request['dept'];
    	$prof_attend_conference->name=$request['name'];
    	$prof_attend_conference->profLevel=$request['profLevel'];
    	$prof_attend_conference->nation=$request['nation'];
		$prof_attend_conference->confName=$request['confName'];
		$prof_attend_conference->startDate=$request['startDate'];
		$prof_attend_conference->endDate=$request['endDate'];
		$prof_attend_conference->comments=$request['comments'];
		$prof_attend_conference->save();
		
		*/
		return redirect('prof_attend_conference')->with('success','新增成功'); //view中未新增顯示成功功能
		
    }
}
